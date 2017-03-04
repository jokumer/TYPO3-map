<?php
defined('TYPO3_MODE') or die();

// Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Jokumer.map',
    'Category',
    [
        'Category' => 'list,show',
        'Location' => 'list',
    ],
    // non-cacheable actions
    []
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Jokumer.map',
    'Geolocate',
    [
        'Map' => 'geolocate,overlay',
        'Location' => 'list,listGeolocate',
    ],
    // non-cacheable actions
    []
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Jokumer.map',
    'Location',
    [
        'Location' => 'list,listGeolocate,show',
        'Category' => 'list',
    ],
    // non-cacheable actions
    []
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Jokumer.map',
    'Map',
    [
        'Map' => 'show,overlay,geolocate',
        'Category' => 'list',
        'Location' => 'list',
    ],
    // non-cacheable actions
    []
);
