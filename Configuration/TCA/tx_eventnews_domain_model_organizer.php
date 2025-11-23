<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_organizer',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
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
        'searchFields' => 'title,description,',
        'iconfile' => 'EXT:eventnews/Resources/Public/Icons/tx_eventnews_domain_model_organizer.svg',
    ],
    'types' => [
        '1' => ['showitem' => '
                title, description, link,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,--palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,'],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_organizer.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_organizer.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 4,
                'eval' => 'trim',
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_organizer.link',
            'config' => [
                'type' => 'link',
                'softref' => 'typolink',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
    ],
];
