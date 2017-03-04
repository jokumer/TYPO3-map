<?php
$locallangFile = 'LLL:EXT:map/Resources/Private/Language/locallang_db_content_reference.xlf';
$locallangFileTCA = 'LLL:EXT:map/Resources/Private/Language/locallang_tca.xlf';
return [
    'ctrl' => [
        'title' => $locallangFile . ':content_reference',
        'label' => 'uid_local',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'type' => 'uid_local:CType',
        'hideTable' => true,
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'rootLevel' => -1,
        // records can and should be edited in workspaces
        'shadowColumnsForMovePlaceholders' => 'tablenames,fieldname,uid_local,table_local,uid_foreign',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'searchFields' => 'uid_local,uid_foreign,tablenames,fieldname,title,description',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-content-text'
        ]
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, fieldname, sorting_foreign, table_local, tablenames, uid_foreign, uid_local'
    ],
    'types' => [
        '0' => [
            'showitem' => 'uid_local, hidden, sys_language_uid, l10n_parent'
        ]
    ],
    'columns' => [
        't3ver_label' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '30'
            ]
        ],
        'sys_language_uid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
                'default' => 0,
                'showIconTable' => true,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0]
                ],
                'foreign_table' => 'tx_map_content_reference',
                'foreign_table_where' => 'AND tx_map_content_reference.uid=###REC_FIELD_l10n_parent### AND tx_map_content_reference.sys_language_uid IN (-1,0)',
                'default' => 0
            ]
        ],
        'l10n_diffsource' => [
            'exclude' => 0,
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'hidden' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => '0'
            ]
        ],
        'fieldname' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.fieldname',
            'config' => [
                'type' => 'input',
                'size' => '30'
            ]
        ],
        'sorting_foreign' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.sorting_foreign',
            'config' => [
                'type' => 'input',
                'size' => '4',
                'max' => '4',
                'eval' => 'int',
                'range' => [
                    'upper' => '1000',
                    'lower' => '10'
                ],
                'default' => 0
            ]
        ],
        'table_local' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.table_local',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'default' => 'tt_content'
            ]
        ],
        'tablenames' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.tablenames',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim'
            ]
        ],
        'uid_foreign' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.uid_foreign',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'eval' => 'int'
            ]
        ],
        'uid_local' => [
            'exclude' => 0,
            'label' => $locallangFile . ':tx_map_content_reference.uid_local',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'size' => 1,
                'eval' => 'int',
                'maxitems' => 1,
                'minitems' => 0,
                'allowed' => 'tt_content'
            ]
        ]
    ]
];
