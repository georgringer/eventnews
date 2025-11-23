<?php

namespace GeorgRinger\Eventnews\ViewHelpers;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CalendarViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * register arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('newsList', 'mixed', '', true);
        $this->registerArgument('demand', Demand::class, '', true);
        $this->registerArgument('firstDayOfWeek', 'integer', '0 for Sunday, 1 for Monday', false, 0);
    }

    public function render(): string
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
                $day['day'] = (string)date('j', $dts); // todo: change back to int cast when https://review.typo3.org/c/Packages/TYPO3.CMS/+/86664 is fixed
                $day['month'] = (int)date('n', $dts);
                $day['year'] = (int)date('Y', $dts);
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

            if (!$item->getEventEnd()) {
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
