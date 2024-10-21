<?php

$tx_eventnews_domain_model_location = [
    'ctrl' => [
        'title'                    => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location',
        'label'                    => 'title',
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'versioningWS'             => true,
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ],
        'searchFields' => 'title,description,lat,lng,link,',
        'iconfile'     => 'EXT:eventnews/Resources/Public/Icons/tx_eventnews_domain_model_location.svg',
    ],
    'types' => [
        '1' => ['showitem' => '

                title, description, link, --palette--;;latlng,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,--palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language'        => ['showitem' => 'sys_language_uid, l10n_parent'],
        'latlng'          => ['showitem' => 'lat, lng'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config'  => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label'       => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table'       => 'tx_eventnews_domain_model_location',
                'foreign_table_where' => 'AND tx_eventnews_domain_model_location.pid=###CURRENT_PID### AND tx_eventnews_domain_model_location.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        't3ver_label' => [
            'label'  => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max'  => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'default'    => 0,
                'items'      => [
                    ['label' => '', 'value' => ''],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config'  => [
                'type' => 'datetime',
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config'  => [
                'type' => 'datetime',
            ],
        ],
        'title' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.description',
            'config'  => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 4,
                'eval' => 'trim',
            ],
        ],
        'lat' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.lat',
            'config'  => [
                'type'     => 'input',
                'size'     => 30,
                'eval'     => 'trim',
                'nullable' => true,
                'default'  => null,
            ],
        ],
        'lng' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.lng',
            'config'  => [
                'type'     => 'input',
                'size'     => 30,
                'eval'     => 'trim',
                'nullable' => true,
                'default'  => null,
            ],
        ],
        'link' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.link',
            'config'  => [
                'type'      => 'link',
                'softref'   => 'typolink',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
    ],
];

$versionInformation = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Information\Typo3Version::class);
if ($versionInformation->getMajorVersion() >= 11) {
    unset($tx_eventnews_domain_model_location['interface']);
    $tx_eventnews_domain_model_location['columns']['sys_language_uid']['config'] = [
        'type' => 'language',
    ];
}

return $tx_eventnews_domain_model_location;
