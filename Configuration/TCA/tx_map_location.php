<?php
$locallangFile = 'LLL:EXT:map/Resources/Private/Language/locallang_db_location.xlf';
$locallangFileTCA = 'LLL:EXT:map/Resources/Private/Language/locallang_tca.xlf';
return [
    'ctrl' => [
        'title' => $locallangFile . ':tx_map_location',
        'label' => 'title',
        'descriptionColumn' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'address,city,country,description,latitude,longitude,title,zip,',
        'iconfile' => 'EXT:map/Resources/Public/Icons/Backend/Model/location.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, address, category, city, country, description, latitude, longitude, title, zip, related_content',
    ],
    'types' => [
        '0' => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                title, description,
                category,
                --palette--;' . $locallangFileTCA . ':palettes.address;address,
                --palette--;' . $locallangFileTCA . ':palettes.map;map,
                --div--;' . $locallangFileTCA . ':tabs.related, related_content,
                --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, hidden, starttime, endtime
            '
        ]
    ],
    'palettes' => [
        '0' => ['showitem' => ''],
        'address' => ['showitem' => 'address, --linebreak--, city, --linebreak--, zip, --linebreak--, country'],
        'map' => ['showitem' => 'geocode, --linebreak--, latitude, longitude'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1, 'flags-multiple'],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ]
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_map_location',
                'foreign_table_where' => 'AND tx_map_location.pid=###CURRENT_PID### AND tx_map_location.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ]
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ]
            ]
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ]
            ]
        ],
        'address' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'category' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_map_category',
                'foreign_table_where' => ' AND tx_map_category.sys_language_uid IN (-1,0) ORDER BY tx_map_category.crdate DESC',
                'items' => [
                    [$locallangFileTCA . ':general.select.none', 0]
                ],
                'maxitems' => '1',
                'minitems' => '0',
                'default' => '',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'city' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'country' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.country',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'description' => [
            'exclude' => 1,
            'label' => $locallangFile . ':tx_map_location.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim'
            ]
        ],
        'geocode' => [
            'exclude' => 0,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFileTCA . ':field.geocode',
            'config' => [
                'type' => 'user',
                'userFunc' => Jokumer\Map\Utility\BackendMapUtility::class . '->renderMap',
                'parameters' => [
                    'entryDefaultLatitude' => 55.65,
                    'entryDefaultLongitude' => 12.08,
                    'entryFieldLatitude' => 'latitude',
                    'entryFieldLongitude' => 'longitude',
                    'mapDefaultZoom' => 14,
                    'mapOptions' => ['displayMap','doGeocoding']
                ]
            ]
        ],
        'latitude' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.latitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
        'longitude' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.longitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
        'related_content' => [
            'exclude' => 1,
            'label' => $locallangFile . ':tx_map_location.related_content',
            'config' => [
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showRemovedLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'showSynchronizationLink' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ]
                ],
                'type' => 'inline',
                'foreign_field' => 'uid_foreign',
                'foreign_label' => 'uid_local',
                'foreign_match_fields' => [
                    'fieldname' => 'related_content'
                ],
                'foreign_selector' => 'uid_local',
                #'foreign_selector_fieldTcaOverride',
                'foreign_sortby' => 'sorting_foreign',
                'foreign_table' => 'tx_map_content_reference',
                'foreign_table_field' => 'tablenames',
                #'foreign_types,
                'minitems' => 0,
                'maxitems' => 99,
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => $locallangFile . ':tx_map_location.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'zip' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_location.zip',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ]
        ]
    ]
];
