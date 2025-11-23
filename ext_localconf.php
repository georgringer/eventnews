<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Eventnews',
    'NewsMonth',
    [
        \GeorgRinger\Eventnews\Controller\NewsController::class => 'month',
    ],
    [
        \GeorgRinger\Eventnews\Controller\NewsController::class => 'month',
    ],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Hide not needed fields in FormEngine
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\FormEngineHook::class;

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news'][\GeorgRinger\News\Hooks\PluginPreviewRenderer::class]['extensionSummary']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\PageLayoutView::class . '->extensionSummary';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\Eventnews\Backend\FormDataProvider\EventNewsRowInitializeNew::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\Backend\EventNewsDataHandlerHook::class;

/***********
 * Extend EXT:news
 */

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'eventnews';

// override language files of news
$overrideModuleLable = (bool)\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('eventnews', 'overrideAdministrationModuleLabel');
if ($overrideModuleLable) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']
        ['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][]
            = 'EXT:eventnews/Resources/Private/Language/Overrides/locallang_modadministration.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']
        ['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][]
            = 'EXT:eventnews/Resources/Private/Language/Overrides/de.locallang_modadministration.xlf';
}
