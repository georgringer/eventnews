<?php
declare(strict_types=1);

namespace GeorgRinger\Eventnews\Hooks;

use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class PageLayoutView
{

    /**
     * Provide an extension summary for the month selection
     */
    public function extensionSummary(array $params, PluginPreviewRenderer $pageLayout)
    {
        /** @var GridColumnItem $item */
        $item = $params['item'];
        if ($item->getRecord()['CType']  === 'eventnews_newsmonth') {
            $pageLayout->getStartingPoint();
            $pageLayout->getTimeRestrictionSetting();
            $pageLayout->getTopNewsRestrictionSetting();
            $pageLayout->getOrderSettings();
            $pageLayout->getCategorySettings();
            $pageLayout->getArchiveSettings();
            $pageLayout->getOffsetLimitSettings();
            $pageLayout->getDetailPidSetting();
            $pageLayout->getListPidSetting();
            $pageLayout->getTagRestrictionSetting();
            $this->getEventRestrictionSetting($pageLayout);
        }
    }

    /**
     * Show the event restriction
     */
    protected function getEventRestrictionSetting(PluginPreviewRenderer $renderer)
    {
        $eventRestriction = (int)$renderer->getFieldFromFlexform('settings.eventRestriction', 'extraEntryEventNews');
        if ($eventRestriction > 0) {
            $languageService = $this->getLanguageService();
            $renderer->tableData[] = [
                $languageService->sL('LLL:EXT:eventnews/Resources/Private/Language/locallang.xlf:flexforms_general.eventRestriction'),
                $languageService->sL('LLL:EXT:eventnews/Resources/Private/Language/locallang.xlf:flexforms_general.eventRestriction.' . $eventRestriction),
            ];
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
