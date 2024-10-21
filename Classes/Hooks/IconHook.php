<?php

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Change news icon if it is an event.
 */
class IconHook
{
    /**
     * @param array $params
     *
     * @return string|null
     */
    public function run(array $params)
    {
        $row = $params['row'];
        if ($row['is_event'] ?? false) {
            return 'ext-news-type-event';
        }

        return null;
    }
}
