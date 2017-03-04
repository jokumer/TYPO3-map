<?php
namespace Jokumer\Map\Controller;

use Jokumer\Map\Controller\AbstractController;
use Jokumer\Map\Domain\Model\Overlay\MapOverlay;
use Jokumer\Map\Domain\Model\Overlay\Marker\Location as OverlayMarkerLocation;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class MapController
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MapController extends AbstractController
{
    /**
     * Show action
     *
     * @return void
     */
    public function showAction()
    {
        $activeCategories = $this->getActiveCategoriesFromPluginSettings();
        $availableCategories = $this->getAvailableCategoriesFromPluginSettings();
        $categories = $this->getCategoriesFromAvailableCategoriesSetActiveCategories($availableCategories, $activeCategories);
        $this->view->assign('categories', $categories);
    }

    /**
     * Overlay action
     *
     * @return void
     */
    public function overlayAction()
    {
        if ($this->request->hasArgument('overlay')) {
            $overlay = $this->request->getArgument('overlay');
        }
        switch ($overlay) {
            // Overlay data for default by categories
            default:
            case 'default':
                if ($this->request->hasArgument('categories')) {
                    $this->getOverlayDataForDefaultByCategories($this->request->getArgument('categories'));
                } else {
                    # throw error
                }
                break;
            // Overlay data for infowindow by identifier
            case 'infowindow':
                if ($this->request->hasArgument('identifier')) {
                    $identifier = $this->request->getArgument('identifier');
                    $this->getOverlayDataForInfowindowByIdentifier($identifier);
                } else {
                    # throw error
                }
                break;
        }
    }

    /**
     * Geolocate action
     *
     * @return void
     */
    public function geolocateAction()
    {
        $filter = null;
        if ($this->settings['map']['options']['geolocate'] && $this->request->hasArgument('geolocate')) {
            $geolocateRequest = $this->request->getArgument('geolocate');
            if (!empty($geolocateRequest)) {
                $bounds = [];
                if ($geolocateRequest['latitude'] !== 0) {
                    $this->geolocationLatitude = $geolocateRequest['latitude'];
                }
                if ($geolocateRequest['longitude'] !== 0) {
                    $this->geolocationLongitude = $geolocateRequest['longitude'];
                }
                $nearestLocation = $this->getNearestLocation();
                if ($nearestLocation instanceof \Jokumer\Map\Domain\Model\Location) {
                    $bounds = [
                        'geoLocation' => [
                            'latitude' => $this->geolocationLatitude,
                            'longitude' => $this->geolocationLongitude
                        ],
                        'nearestLocation' => [
                            'latitude' => $nearestLocation->getLatitude(),
                            'longitude' => $nearestLocation->getLongitude()
                        ]
                    ];
                }
                // Init JSON view (add page typeNum for debugging purpose)
                if ($this->isAjaxRequest) {
                    $this->setJsonViewForAjaxRequest();
                }
                // Assing data to current view
                $this->view->assign('bounds', $bounds);
                if ($this->view instanceof JsonView) {
                    $this->view->setVariablesToRender(['bounds']);
                }
            } else {
                // throw error
            }
            
        } else {
            // throw error
        }
    }

    /**
     * Get overlay data for default by categories
     * Returns JSON view for default overlays of markers
     *
     * @param string $categories Commaseparated list of category uids
     * @return void
     */
    private function getOverlayDataForDefaultByCategories($categories)
    {
        if ($categories) {
            // Get Overlays
            $mapOverlay = $this->objectManager->get(MapOverlay::class);
            // @todo: Avoid SQL request on tables, which are not planned to be shown in map!
            // Add marker: location
            $mapOverlay->addMarkers($this->locationRepository
                ->overrideObjectType(OverlayMarkerLocation::class)
                ->findAllByCategories($categories));
            // Init JSON view (add page typeNum for debugging purpose)
            if ($this->isAjaxRequest) {
                $this->setJsonViewForAjaxRequest();
            }
            // Assing data to current view
            $this->view->assign('overlay', [
                'type' => 'default',
                'markers' => $mapOverlay->getMarkers()
            ]);
            if ($this->view instanceof JsonView) {
                $this->view->setVariablesToRender(['overlay']);
            }
        } else {
            # throw error
        }
    }

    /**
     * Get overlay data for infowindow by identifier
     * Fetches entry data by reflecting class names for repository request
     * Returns HTML view for overlay infowindow of an object
     *
     * @param string $identifier Identifier by class name and the uid of the object as string
     * @return void
     */
    private function getOverlayDataForInfowindowByIdentifier($identifier)
    {
        if ($identifier) {
            list($entryName, $uid) = GeneralUtility::trimExplode('|', $identifier, false, 2);
            if ($entryName && intval($uid)) {
                $repositoryName = strtolower($entryName) . 'Repository';
                if ($this->$repositoryName) {
                    $entryData = $this->$repositoryName->findByUid(intval($uid));
                    if ($entryData) {
                        // Set overlay configuration
                        $overlayConfig = [
                            'type' => 'infowindow',
                            'entryName' => $entryName,
                            'entryData' => $entryData,
                        ];
                        // Get additional overlay configurations from TS
                        if (isset($this->settings['map']['overlays']['infowindow']['category'][$entryData->getCategory()->getUid()])) {
                            $categoryConfig = $this->settings['map']['overlays']['infowindow']['category'][$entryData->getCategory()->getUid()];
                            if (isset($categoryConfig['template'])) {
                                $overlayConfig['template'] = $categoryConfig['template'];
                            }
                            if (isset($categoryConfig['linkPid'])) {
                                $overlayConfig['linkPid'] = intval($categoryConfig['linkPid']);
                            }
                        }
                        // Assign overlay configuration
                        $this->view->assign('overlay', $overlayConfig);
                    } else {
                        # throw error
                    }
                } else {
                    # throw error
                }
            } else {
                # throw error
            }
        } else {
            # throw error
        }
    }

    /**
     * Get active categories from plugin settings
     *
     * @return void
     */
    private function getActiveCategoriesFromPluginSettings()
    {
        $activeCategories = null;
        if (isset($this->settings['map']['overlays']['categories']['active']) && strlen($this->settings['map']['overlays']['categories']['active'])) {
            $activeCategories = $this->categoryRepository->findAllByCategories($this->settings['map']['overlays']['categories']['active']);
        }
        return $activeCategories;
    }

    /**
     * Get available categories from plugin settings
     *
     * @return void
     */
    private function getAvailableCategoriesFromPluginSettings()
    {
        $availableCategories = null;
        if (isset($this->settings['map']['overlays']['categories']['available']) && strlen($this->settings['map']['overlays']['categories']['available'])) {
            $availableCategories = $this->categoryRepository->findAllByCategories($this->settings['map']['overlays']['categories']['available']);
        }
        return $availableCategories;
    }

    /**
     * Get categories from available categories set active categories
     *
     * @param QueryResult|null $availableCategories
     * @param QueryResult|null $activeCategories
     * @return QueryResult|null $categories
     */
    private function getCategoriesFromAvailableCategoriesSetActiveCategories(
        QueryResult $availableCategories = null,
        QueryResult $activeCategories = null
    ) {
        $categories = null;
        if ($availableCategories !== null) {
            $categories = $availableCategories;
            if (!empty($categories)) {
                if ($activeCategories !== null && !empty($activeCategories)) {
                    foreach ($categories as $key => $category) {
                        foreach ($activeCategories as $activeCategory) {
                            if ($category->getUid() === $activeCategory->getUid()) {
                                $categories[$key]->setIsActive(true);
                            }
                        }
                    }
                }
            }
        }
        return $categories;
    }
}
