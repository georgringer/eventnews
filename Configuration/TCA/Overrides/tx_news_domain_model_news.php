<?php

$fields = [
    'is_event' => [
        'exclude' => true,
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_news.is_event',
        'onChange' => 'reload',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
    'full_day' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_news.full_day',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
    'event_end' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_news.event_end',
        'config' => [
            'type' => 'datetime',
        ],
    ],
    'organizer' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_organizer',
        'config' => [
            'default' => 0,
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => '', 'value' => 0],
            ],
            'foreign_table' => 'tx_eventnews_domain_model_organizer',
            'foreign_table_where' => 'ORDER BY tx_eventnews_domain_model_organizer.title',
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],
    'location' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location',
        'config' => [
            'default' => 0,
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => '', 'value' => 0],
            ],
            'foreign_table' => 'tx_eventnews_domain_model_location',
            'foreign_table_where' => 'ORDER BY tx_eventnews_domain_model_location.title',
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],
    'organizer_simple' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.organizer_simple',
        'config' => [
            'type' => 'input',
            'size' => 15,
        ],
    ],
    'location_simple' => [
        'exclude' => true,
        'displayCond' => 'FIELD:is_event:>:0',
        'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.location_simple',
        'config' => [
            'type' => 'input',
            'size' => 15,
        ],
    ],
];

$GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['palette_event'] = [
    'canNotCollapse' => true,
    'showitem' => 'event_end,full_day,',
];
$GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['palette_eventfields'] = [
    'canNotCollapse' => true,
    'showitem' => 'organizer,organizer_simple, --linebreak--,location,location_simple',
];
$GLOBALS['TCA']['tx_news_domain_model_news']['ctrl']['typeicon_classes']['userFunc'] = \GeorgRinger\Eventnews\Hooks\IconHook::class . '->run';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', 'is_event,--palette--;;palette_event', '', 'after:datetime');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', ',--div--;LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tab.eventnews,--palette--;;palette_eventfields', '', 'before:--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media');
