<?php

defined('TYPO3') or exit;

// Add eventnews icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_be.xlf:eventnews-folder',
    'value' => 'eventnews',
    'icon'  => 'apps-pagetree-folder-contains-eventnews',
];
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-eventnews'] = 'apps-pagetree-folder-contains-eventnews';
