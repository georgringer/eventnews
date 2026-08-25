<?php

use GeorgRinger\Eventnews\Tca\CountryItemsProcFunc;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

// TCA type "country" arrived with TYPO3 14, see Feature-99911-NewTCATypeCountry.
// On 13.4 the same selection is built from the Country API, which exists since
// 12.3. Both variants store the ISO 3166-1 alpha-2 code, so the persisted value
// and everything reading it stays the same across cores.
if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 14) {
    $countryFieldConfig = [
        'type' => 'country',
        'labelField' => 'localizedName',
        'sortItems' => ['label' => 'asc'],
    ];
} else {
    $countryFieldConfig = [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            ['label' => '', 'value' => ''],
        ],
        'itemsProcFunc' => CountryItemsProcFunc::class . '->populate',
        'default' => '',
    ];
}

$tx_eventnews_domain_model_location = [
    'ctrl' => [
        'title' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location',
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
        'searchFields' => 'title,description,address,zip,city,state,municipality,phone,email,lat,lng,link,',
        'iconfile' => 'EXT:eventnews/Resources/Public/Icons/tx_eventnews_domain_model_location.svg',
    ],
    'types' => [
        '1' => ['showitem' => '

                title, description,
                --palette--;LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.palette.address;address,
                --palette--;LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.palette.contact;contact,
                link, --palette--;;latlng,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,--palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent'],
        'latlng' => ['showitem' => 'lat, lng'],
        'address' => ['showitem' => 'address, --linebreak--, zip, city, --linebreak--, state, municipality, --linebreak--, country'],
        'contact' => ['showitem' => 'phone, email'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 4,
                'eval' => 'trim',
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'zip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.zip',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'city' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'state' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.state',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'municipality' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.municipality',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'country' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.country',
            'config' => $countryFieldConfig,
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.phone',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.email',
            'config' => [
                'type' => 'email',
                'size' => 30,
            ],
        ],
        'lat' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.lat',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'nullable' => true,
                'default' => null,
            ],
        ],
        'lng' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.lng',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'nullable' => true,
                'default' => null,
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:tx_eventnews_domain_model_location.link',
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

return $tx_eventnews_domain_model_location;
