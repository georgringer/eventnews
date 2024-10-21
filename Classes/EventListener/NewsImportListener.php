<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\EventListener;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Event\NewsImportPostHydrateEvent;

/**
 * Persist dynamic data of import.
 */
class NewsImportListener
{
    public function __invoke(NewsImportPostHydrateEvent $event)
    {
        $importData = $event->getImportItem();

        /** @var \GeorgRinger\Eventnews\Domain\Model\News $news */
        if (is_array($importData['_dynamicData'])) {
            if (isset($importData['_dynamicData']['location'])) {
                $event->getNews()->setLocationSimple(trim($importData['_dynamicData']['location']));
            }
            if (!empty($importData['_dynamicData']['datetime_end'])) {
                $date = new \DateTime();
                $date->setTimestamp($importData['_dynamicData']['datetime_end']);
                $event->getNews()->setEventEnd($date);
            }
        }
    }
}
