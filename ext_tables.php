<?php
defined('TYPO3_MODE') or die();

$locallangPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('map') . 'Resources/Private/Language/';

// Category
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_map_category');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_map_category',
    $locallangPath . 'locallang_csh_db_category.xlf'
);

// Location
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_map_location');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_map_location',
    $locallangPath . 'locallang_csh_db_location.xlf'
);
