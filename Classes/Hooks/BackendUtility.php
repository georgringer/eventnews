<?php

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUtility extends \GeorgRinger\News\Hooks\BackendUtility
{
    /**
     * @param array|string $params
     * @param array $reference
     */
    public function update(&$params, &$reference)
    {
        if ($params['selectedView'] === 'News->month') {
            $removedFields = $this->removedFieldsInListView;

            $this->deleteFromStructure($params['dataStructure'], $removedFields);
        }
    }
}
