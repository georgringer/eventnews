<?php

defined('TYPO3') or die();

$pluginName = 'news_month';
$pluginNameForLabel = $pluginName === 'pi1' ? 'news_list' : $pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'eventnews',
    'NewsMonth',
    'LLL:EXT:eventnews/Resources/Private/Language/locallang_be.xlf:plugin.news_month.title',
    null,
    'news'
);

$contentTypeName = 'eventnews_newsmonth';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:news/Configuration/FlexForms/flexform_news_list.xml',
    'eventnews_newsmonth'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = 'ext-news-plugin-' . str_replace('_', '-', $pluginNameForLabel);

$GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['previewRenderer'] = \GeorgRinger\News\Hooks\PluginPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_news_domain_model_news');