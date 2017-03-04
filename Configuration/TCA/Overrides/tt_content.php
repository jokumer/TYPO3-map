<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    // Register plugin - Map
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Jokumer.Map',
        'Map',
        'LLL:EXT:map/Resources/Private/Language/locallang_tca.xlf:plugin.map.title'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['map_map']='layout, select_key, pages, recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['map_map'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('map_map',
        'FILE:EXT:map/Configuration/FlexForm/flexform_map.xml');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content.pi_flexform.map_map.list',
        'EXT:map/Resources/Private/Language/locallang_csh_flexform_map.xlf');
    // Register plugin - Category
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Jokumer.Map',
        'Category',
        'LLL:EXT:map/Resources/Private/Language/locallang_tca.xlf:plugin.category.title'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['map_category']='layout, select_key, pages, recursive';
    // Register plugin - Location
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Jokumer.Map',
        'Location',
        'LLL:EXT:map/Resources/Private/Language/locallang_tca.xlf:plugin.location.title'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['map_location']='layout, select_key, pages, recursive';
});