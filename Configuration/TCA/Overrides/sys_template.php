<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    // Register static TypoScript resource
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('map', 'Configuration/TypoScript',
        'Map');
});