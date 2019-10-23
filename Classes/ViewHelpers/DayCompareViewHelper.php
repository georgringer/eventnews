<?php

namespace GeorgRinger\Eventnews\ViewHelpers;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\Eventnews\Domain\Model\News as EventNews;
use GeorgRinger\News\Domain\Model\News;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class DayCompareViewHelper extends AbstractViewHelper
{

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('newsItem', News::class, 'News item', true);
        $this->registerArgument('demand', Demand::class, 'demand object', true);
        parent::initializeArguments();
    }

    /**
     * @param array|null $arguments
     * @return bool
     */
    public function render()
    {
        $found = false;

        /** @var Demand $demand */
        $demand = $this->arguments['demand'];
        /** @var EventNews $newsItem */
        $newsItem = $this->arguments['newsItem'];

        $currentDay = \DateTime::createFromFormat('d-m-Y H:i:s', sprintf(
            '%s-%s-%s 00:00:00', $demand->getDay(), $demand->getMonth(), $demand->getYear()));
        $currentDay->setTimezone($newsItem->getDatetime()->getTimezone());
        $newsBeginDate = clone $newsItem->getDatetime();
        $newsBeginDate->setTime(0, 0);

        if ($newsItem->getEventEnd() === null) {
            $newsEndDate = clone $newsBeginDate;
            $newsEndDate->setTime(23, 59, 59);
        } else {
            $newsEndDate = clone $newsItem->getEventEnd();
            $newsEndDate->setTime(0, 0);
        }

        if ($newsBeginDate <= $currentDay && $newsEndDate >= $currentDay) {
            $found = true;
        }

        return $found;
    }
}
