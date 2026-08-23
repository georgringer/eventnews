<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Backend\FormDataProvider;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Fill the news records with default values
 */
class EventNewsRowInitializeNew implements FormDataProviderInterface
{
    /**
     * @param array $result
     * @return array
     */
    public function addData(array $result): array
    {
        if ($result['command'] !== 'new' || $result['tableName'] !== 'tx_news_domain_model_news') {
            return $result;
        }

        if ($result['pageTsConfig']['tx_news.']['newRecordAsEvent'] ?? false) {
            $result['databaseRow']['is_event'] = 1;
            $result['databaseRow']['datetime'] = 0;
        }

        return $result;
    }
}
