<?php
namespace Jokumer\Map\Utility;

use Jokumer\Map\Configuration\ExtConf;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class BackendGeocoder
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendMapUtility
{

    /**
     * Render Google Maps Geocode
     * Generate coordinates and view map using Google Maps API
     * https://developers.google.com/maps/documentation/geocoding
     * Needs Geocode API key from Google https://developers.google.com/maps/documentation/geocoding/get-api-key
     * Uses GMap javascript from Christian Brinkert <christian.brinkert@googlemail.com>
     * 
     * @param array $fieldConfiguration Array (passed by reference) which contains the current information about the current field being rendered
     * @param \TYPO3\CMS\Backend\Form\FormEngine $parentFormObject Reference to the parent object (an instance of the TYPO3\CMS\Backend\Form\FormEngine class)
     * @return string
     */
    public function renderMap($fieldConfiguration, $parentFormObject)
    {
        $settings = $this->getDefaultSettings($fieldConfiguration);
        return $this->render($settings);
    }

    /**
     * Get default settings
     * 
     * @param array $config
     * @return array
     */
    private function getDefaultSettings($fieldConfiguration) {
        $extRelPath = ExtensionManagementUtility::extRelPath('map');
        $geocodeJSFile = $extRelPath . 'Resources/Public/JavaScript/Backend/txmap.min.js';
        $extConf = GeneralUtility::makeInstance(ExtConf::class);
        $googleMapsApiKey = $extConf->getGoogleMapsApiKey();
        $googleMapsLibraryUrl = 'https://maps.googleapis.com/maps/api/js?key=' . $googleMapsApiKey;
        $settings = [
            'googleMapsApiKey' => $googleMapsApiKey,
            'googleMapsLibraryUrl' => $googleMapsLibraryUrl,
            'geocodeJSFile' => $geocodeJSFile,
            'fieldConfiguration' => $fieldConfiguration
        ];
        return $settings;
    }

    /**
     * Render
     *
     * @param array $settings
     * @return string
     */
    protected function render($settings) {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var StandaloneView $view */
        $view = $objectManager->get(StandaloneView::class);
        $view->setFormat('html');
        $templateRootPath = ExtensionManagementUtility::extPath('map') . 'Resources/Private/Backend/';
        $templatePathAndFilename = $templateRootPath . 'Map.html';
        $view->setTemplatePathAndFilename($templatePathAndFilename);
        $view->assign('settings', $settings);
        return $view->render();
    }
}
