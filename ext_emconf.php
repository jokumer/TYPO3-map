<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Map',
    'description' => 'Google Maps API3 plugin',
    'category' => 'plugin',
    'author' => 'J.Kummer',
    'author_email' => 'typo3 et enobe dot de',
    'author_company' => 'enobe.de',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Jokumer\\Map\\' => 'Classes',
        ],
    ],
];
