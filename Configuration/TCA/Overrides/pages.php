<?php
defined('TYPO3_MODE') or die();

// Override news icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:eventnews/Resources/Private/Language/locallang_be.xlf:eventnews-folder',
    1 => 'eventnews',
    2 => 'apps-pagetree-folder-contains-eventnews'
];
