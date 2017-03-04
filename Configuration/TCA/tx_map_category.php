<?php
$locallangFile = 'LLL:EXT:map/Resources/Private/Language/locallang_db_category.xlf';
return [
    'ctrl' => [
        'title' => $locallangFile . ':tx_map_category',
        'label' => 'title',
        'descriptionColumn' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY crdate DESC',
        'dividers2tabs' => true,
        'versioningWS' => 2,
        'versioning_followPages' => true,
        'languageField' => 'sys_language_uid',
        'thumbnail' => 'icon',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'description,title,title_language_overlay,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('map') . 'Resources/Public/Icons/Backend/Model/category.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, description, icon, parent, pidy, title, title_language_overlay',
    ],
    'types' => [
        '0' => [
            'showitem' => '
            sys_language_uid, l10n_parent, l10n_diffsource,
            title, description,
            icon,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, hidden, starttime, endtime
        '
        ]
    ],
    'palettes' => [
        '0' => ['showitem' => ''],
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
                'foreign_table' => 'tx_map_category',
                'foreign_table_where' => 'AND tx_map_category.pid=###CURRENT_PID### AND tx_map_category.sys_language_uid IN (-1,0)',
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
                'size' => 13,
                'max' => 20,
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
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ]
            ]
        ],
        'description' => [
            'exclude' => 1,
            'label' => $locallangFile . ':tx_map_category.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim'
            ]
        ],
        'icon' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_category.icon',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('icon', [
                'maxitems' => 1,
                'appearance' => [
                    'headerThumbnail' => ['height' => '30c', 'width' => '30',],
                    'useSortable' => 0
                ],
                // Unset showitem (basicoverlayPalette and filePalette) -> ERROR: #1300098528: Incorrect reference to original file given for FileReference
                #'foreign_types' => [
                #    '0' => ['showitem' => ''],
                #    '1' => ['showitem' => ''],
                #    '2' => ['showitem' => ''],
                #    '3' => ['showitem' => ''],
                #    '4' => ['showitem' => ''],
                #    '5' => ['showitem' => '']
                #]
            ])
        ],
        'parent' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_category.parent',
            'config' => [
                'minitems' => 0,
                'maxitems' => 1,
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'tx_map_category',
                'foreign_table_where' => ' AND tx_map_category.sys_language_uid IN (-1,0) ORDER BY tx_map_category.crdate DESC',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'expandAll' => true,
                        'showHeader' => true,
                        'maxLevels' => 99,
                    ]
                ],
                'default' => 0
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => $locallangFile . ':tx_map_category.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'title_language_overlay' => [
            'exclude' => 1,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => $locallangFile . ':tx_map_category.title_language_overlay',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ]
    ]
];