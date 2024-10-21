<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FormEngineHook.
 */
class FormEngineHook
{
    protected const FIELDS = 'full_day,event_end,organizer,organizer_simple,location,location_simple';

    /**
     * Remove rendered field from output if it is no event.
     *
     * @param string $table
     * @param string $field
     * @param array  $row
     * @param string $out
     *
     * @return void
     */
    public function getSingleField_postProcess($table, $field, $row, &$out)
    {
        if ($table === 'tx_news_domain_model_news' && $row['is_event'] == 0) {
            if (GeneralUtility::inList(self::FIELDS, $field)) {
                $out = '';
            }
        }
    }
}
