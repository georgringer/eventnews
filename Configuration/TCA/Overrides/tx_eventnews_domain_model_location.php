<?php

if ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() >= 14) {
    unset($GLOBALS['TCA']['tx_eventnews_domain_model_location']['ctrl']['searchFields'], $GLOBALS['TCA']['tx_eventnews_domain_model_organizer']['ctrl']['searchFields']);
}
