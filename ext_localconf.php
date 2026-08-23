<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use GeorgRinger\Eventnews\Controller\NewsController;
use GeorgRinger\Eventnews\Hooks\FormEngineHook;
use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use GeorgRinger\Eventnews\Hooks\PageLayoutView;
use GeorgRinger\Eventnews\Backend\FormDataProvider\EventNewsRowInitializeNew;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
use GeorgRinger\Eventnews\Hooks\Backend\EventNewsDataHandlerHook;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

ExtensionUtility::configurePlugin(
    'Eventnews',
    'NewsMonth',
    [
        NewsController::class => 'month',
    ],
    [
        NewsController::class => 'month',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Hide not needed fields in FormEngine
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass']['eventnews']
    = FormEngineHook::class;

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news'][PluginPreviewRenderer::class]['extensionSummary']['eventnews']
    = PageLayoutView::class . '->extensionSummary';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][EventNewsRowInitializeNew::class] = [
    'depends' => [
        DatabaseRowInitializeNew::class,
    ]
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['eventnews'] =
    EventNewsDataHandlerHook::class;

/***********
 * Extend EXT:news
 */

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'eventnews';

// override language files of news
$overrideModuleLable = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('eventnews', 'overrideAdministrationModuleLabel');
if ($overrideModuleLable) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']
        ['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][]
            = 'EXT:eventnews/Resources/Private/Language/Overrides/locallang_modadministration.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']
        ['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][]
            = 'EXT:eventnews/Resources/Private/Language/Overrides/de.locallang_modadministration.xlf';
}
