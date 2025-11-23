<?php

namespace GeorgRinger\Eventnews\Hooks\Backend;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;

class EventNewsDataHandlerHook
{
    /**
     * Save a new news record as event if stated by tsconfig
     *
     * @param string $status status
     * @param string $table table name
     * @param int $recordUid id of the record
     * @param array $fields fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject parent Object
     */
    public function processDatamap_postProcessFieldArray(
        $status,
        $table,
        $recordUid,
        array &$fields,
        \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject
    ) {
        if ($status === 'new' && $table === 'tx_news_domain_model_news') {
            $tsconfig = BackendUtility::getPagesTSconfig($fields['pid']);
            if ((int)($tsconfig['tx_news.']['newRecordAsEvent'] ?? 0) === 1) {
                $fields['is_event'] = 1;
            }
        }
    }
}
