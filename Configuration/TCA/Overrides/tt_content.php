<?php

use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die;

$pluginConfig = ['news_month', 'news_month_detail'];
foreach ($pluginConfig as $pluginName) {
    $pluginNameForLabel = $pluginName === 'pi1' ? 'news_list' : $pluginName;
    ExtensionUtility::registerPlugin(
        'eventnews',
        GeneralUtility::underscoredToUpperCamelCase($pluginName),
        'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:plugin.' . $pluginNameForLabel . '.title',
        'ext-news-type-event',
        'news',
        'LLL:EXT:eventnews/Resources/Private/Language/locallang_db.xlf:plugin.' . $pluginNameForLabel . '.description',
    );
    
    $contentTypeName = 'eventnews_' . str_replace('_', '', $pluginName);
    
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:news/Configuration/FlexForms/flexform_news_list.xml',
        $contentTypeName
    );
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$contentTypeName] = 'ext-news-plugin-' . str_replace('_', '-', $pluginNameForLabel);
    
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['previewRenderer'] = PluginPreviewRenderer::class;
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
}
