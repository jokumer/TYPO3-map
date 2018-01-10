<?php
namespace Jokumer\Map\Controller;

use Jokumer\Map\Configuration\ExtConf;
use Jokumer\Map\Domain\Repository\CategoryRepository;
use Jokumer\Map\Domain\Repository\LocationRepository;
use Jokumer\Map\Utility\TypoScriptUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Class AbstractController
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractController extends ActionController
{
    /**
     * Geolocation latitude
     *
     * @var double
     */
    protected $geolocationLatitude = 0;

    /**
     * Geolocation longitude
     *
     * @var double
     */
    protected $geolocationLongitude = 0;

    /**
     * Is ajax request
     *
     * @var boolean
     */
    protected $isAjaxRequest = false;

    /**
     * CategoryRepository
     *
     * @var CategoryRepository|null
     */
    protected $categoryRepository = null;

    /**
     * LocationRepository
     *
     * @var LocationRepository|null
     */
    protected $locationRepository = null;

    /**
     * Inject categoryRepository
     *
     * @param CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Inject locationRepository
     *
     * @param LocationRepository $locationRepository
     * @return void
     */
    public function injectLocationRepository(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Initialize actions
     *
     * @return void
     */
    protected function initializeAction()
    {
        parent::initializeAction();
        $this->detectAjaxRequest($this->settings['map']['overlays']['request']['uri']['pageTypeNum']);
        $this->detectAjaxRequest($this->settings['geolocate']['request']['uri']['pageTypeNum']);
        $this->includeJs();
    }

    /**
     * Initialize view
     *
     * @param ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
    }

    /**
     * Set JsonView for ajax request
     *
     * @return void
     */
    protected function setJsonViewForAjaxRequest()
    {
        $this->view = $this->objectManager->get(JsonView::class);
        $this->view->setControllerContext($this->controllerContext);
    }

    /**
     * Detect ajax request
     * Sets var isAjaxRequest, on XMLHttpRequest or if current TSFE typeNum matches pageTypeNum
     *
     * @param int $pageTypeNum Default null
     * @return void
     */
    protected function detectAjaxRequest($pageTypeNum = null)
    {
        if ((intval($pageTypeNum) && $GLOBALS['TSFE']->type === intval($pageTypeNum))
            || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        ) {
            $this->isAjaxRequest = true;
        }
    }

    /**
     * Include Js
     * TypoScript configurable including of JS
     *
     * @return void
     */
     #$pageRender = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
     #$pageRender->addJsFooterFile($jsFile, 'text/javascript', true, false, '', true);
    protected function includeJs() {
        // Include default JS
        if (isset($this->settings['general']['includeJs']['tx_map']['enableDefault'])
            && $this->settings['general']['includeJs']['tx_map']['enableDefault'] == 1
        ) {
          $pageRender = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
          $pageRender->addJsFooterFile(
                ExtensionManagementUtility::siteRelPath('map') . 'Resources/Public/JavaScript/Frontend/txmap.min.js',
                'text/javascript',
                false,
                false,
                '',
                false,
                '|',
                (bool) $this->settings['map']['includeJs']['tx_map_map']['async'],
                ''
            );
        }
        // Include Google Maps API + APIkey
        if (isset($this->settings['general']['includeJs']['googleMapsApi']['enableDefault'])
            && $this->settings['general']['includeJs']['googleMapsApi']['enableDefault'] == 1
        ) {
            $extConf = GeneralUtility::makeInstance(ExtConf::class);
            $googleMapsApiKey = $extConf->getGoogleMapsApiKey();
            $pageRender = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
            $pageRender->addJsFooterFile(
                'https://maps.googleapis.com/maps/api/js?key=' . $googleMapsApiKey . '&libraries=geometry&callback=initializeGoogleMap',
                'text/javascript',
                false,
                false,
                '',
                false,
                '|',
                (bool) $this->settings['map']['includeJs']['googleMapsApi']['async'],
                ''
            );
        }
    }

    /**
     * Get locations
     *
     * @param null $filter
     * @param integer $limit
     * @param boolean $calulcateDistance
     * @param double $radius
     * @return array|\Jokumer\Map\Domain\Repository\QueryResultInterface|null
     */
    protected function getLocations($filter = null, $limit = 1000, $calulcateDistance = 1, $radius = 3958.75) {
        $locations = null;
        switch($filter) {
            case 'radial':
                $radialSearchParameters = [
                    'latitude' => $this->geolocationLatitude,
                    'longitude' => $this->geolocationLongitude,
                    'radius' => $radius,
                    'calulcateDistance' => $calulcateDistance,
                    #'categories' => ,
                    'limit' => $limit,
                    'orderBy' => 'distance',
                ];
                $locations = $this->locationRepository->findLocationsInRadius($radialSearchParameters);
                break;
            default:
                $locations = $this->locationRepository->findAll();
                break;
        }
        return $locations;
    }

    /**
     * Get nearest location
     *
     * @return \Jokumer\Map\Domain\Model\Location|null
     */
    protected function getNearestLocation() {
        $location = null;
        $filter = 'radial';
        $limit = 1;
        $locations = $this->getLocations($filter, $limit);
        if (count($locations) === 1) {
            $location = $locations->getFirst();
        }
        return $location;
    }
}
