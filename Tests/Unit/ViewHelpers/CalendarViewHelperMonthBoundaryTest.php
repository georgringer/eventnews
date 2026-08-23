<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\ViewHelpers;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\Eventnews\ViewHelpers\CalendarViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CalendarViewHelperMonthBoundaryTest extends UnitTestCase
{
    /**
     * A calendar grid is padded with days of the surrounding months.
     * dayBelongsToCurrentMonth has to be false for every one of them - the
     * template uses that flag to decide whether a cell renders its content.
     */
    #[Test]
    #[DataProvider('monthProvider')]
    public function dayBelongsToCurrentMonthOnlyForDaysOfThatMonth(int $month, int $year, int $firstDayOfWeek): void
    {
        $paddingDays = 0;

        foreach ($this->renderWeeks($month, $year, $firstDayOfWeek) as $week) {
            foreach ($week as $day) {
                $belongsToRenderedMonth = $day['month'] === $month && $day['year'] === $year;

                if (!$belongsToRenderedMonth) {
                    $paddingDays++;
                }

                self::assertSame(
                    $belongsToRenderedMonth,
                    $day['dayBelongsToCurrentMonth'],
                    sprintf(
                        'Wrong flag for %04d-%02d-%02d while rendering %04d-%02d',
                        $day['year'],
                        $day['month'],
                        (int)$day['day'],
                        $year,
                        $month
                    )
                );
            }
        }

        self::assertGreaterThan(0, $paddingDays, 'This month has no padding days, so it proves nothing');
    }

    public static function monthProvider(): array
    {
        return [
            // 2015-03-01 is a Sunday, so the grid has no leading days but four
            // trailing ones from April - the case reported in issue #216.
            'trailing days only' => [3, 2015, 0],
            // 2015-05-01 is a Friday: leading days from April, trailing from June.
            'leading and trailing days' => [5, 2015, 0],
            // Year boundary, trailing days belong to the next year.
            'december' => [12, 2015, 0],
            // Year boundary, leading days belong to the previous year.
            'january' => [1, 2016, 0],
            'week starting on monday' => [5, 2015, 1],
        ];
    }

    private function renderWeeks(int $month, int $year, int $firstDayOfWeek): array
    {
        $demand = new Demand();
        $demand->setMonth($month);
        $demand->setYear($year);

        $variableProvider = new StandardVariableProvider();

        $viewHelper = new CalendarViewHelper();
        $viewHelper->setArguments([
            'newsList' => [],
            'demand' => $demand,
            'firstDayOfWeek' => $firstDayOfWeek,
        ]);

        $container = new \ReflectionProperty(AbstractViewHelper::class, 'templateVariableContainer');
        $container->setValue($viewHelper, $variableProvider);

        $weeks = [];
        $viewHelper->setRenderChildrenClosure(static function () use ($variableProvider, &$weeks): string {
            $weeks = $variableProvider->get('weeks');
            return '';
        });

        $viewHelper->render();

        return $weeks;
    }
}
