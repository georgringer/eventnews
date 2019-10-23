<?php

namespace GeorgRinger\Eventnews\ViewHelpers;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class CalendarViewHelper extends AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * register arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('newsList', 'mixed', '', true);
        $this->registerArgument('demand', Demand::class, '', true);
        $this->registerArgument('firstDayOfWeek', 'integer', '0 for Sunday, 1 for Monday', false, 0);
    }

    /**
     * @return string Rendered result
     */
    public function render()
    {
        /** @var Demand $demand */
        $demand = $this->arguments['demand'];
        $month = $demand->getMonth();
        $year = $demand->getYear();

        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $dayOfWeekOfFirstDay = (int)date('w', $firstDayOfMonth);

        $firstDayOfCalendar = 1 - $dayOfWeekOfFirstDay + $this->arguments['firstDayOfWeek'];
        $ld = (int)date('t', $firstDayOfMonth);
        if ($firstDayOfCalendar > 1) {
            $firstDayOfCalendar -= 7;
        }

        $weeks = [];
        $inCurrentMonthBefore = false;
        $inCurrentMonthAfter = false;

        while ($firstDayOfCalendar <= $ld) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $day = [];
                $dts = mktime(0, 0, 0, $month, $firstDayOfCalendar, $year);
                $currentDay = (int)date('j', $dts);

                if ($inCurrentMonthBefore && $currentDay === 1) {
                    $inCurrentMonthAfter = true;
                }

                if ($currentDay === 1) {
                    $inCurrentMonthBefore = true;
                }

                $day['dayBelongsToCurrentMonth'] = $inCurrentMonthBefore;
                $day['ts'] = $dts;
                $day['day'] = (int)date('j', $dts);
                $day['month'] = (int)date('n', $dts);
                $day['curmonth'] = $day['month'] == $month;
                $day['curday'] = date('Ymd') == date('Ymd', $day['ts']);

                if ($inCurrentMonthBefore && !$inCurrentMonthAfter) {
                    $t = \DateTime::createFromFormat('d-m-Y H:i:s', sprintf('%s-%s-%s 00:00:01', $day['day'], $month, $year));
                    $day['news'] = $this->getNewsForDay($this->arguments['newsList'], $t);
                }
                $firstDayOfCalendar++;
                $week[] = $day;
            }
            $weeks[] = $week;
        }

        $this->templateVariableContainer->add('weeks', $weeks);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove('weeks');

        return $output;
    }

    /**
     * @param object $newsList
     * @param \DateTime $currentDay
     * @return array
     */
    protected function getNewsForDay($newsList, $currentDay)
    {
        $relevantNews = [];
        foreach ($newsList as $item) {
            /** @var \GeorgRinger\Eventnews\Domain\Model\News $item */
            $newsBeginDate = $item->getDatetime()->format('Y-m-d');
            $day = date('Y-m-d', $currentDay->getTimestamp());

            if ($item->getEventEnd() == 0) {
                if ($newsBeginDate === $day) {
                    $relevantNews[] = $item;
                }
            } else {
                $newsEndDate = clone $item->getEventEnd();
                $newsEndDate->setTime(23, 59, 59);
                $newsBeginDate = clone $item->getDatetime();
                $newsBeginDate->setTime(0, 0);
                $currentDay->setTime(0, 0);

                if ($newsBeginDate <= $currentDay && $newsEndDate >= $currentDay) {
                    $relevantNews[] = $item;
                }
            }
        }

        return $relevantNews;
    }
}
