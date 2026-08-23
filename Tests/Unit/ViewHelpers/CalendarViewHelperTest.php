<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\ViewHelpers;

use GeorgRinger\Eventnews\Domain\Model\News;
use GeorgRinger\Eventnews\ViewHelpers\CalendarViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CalendarViewHelperTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('newsOfADayProvider')]
    public function getCorrectNewsOfADay(string $day, string $list): void
    {
        $method = new \ReflectionMethod(CalendarViewHelper::class, 'getNewsForDay');
        $newsOfGivenDay = $method->invoke(
            new CalendarViewHelper(),
            self::generateNewsList(),
            new \DateTime($day)
        );

        $newsTitles = [];
        foreach ($newsOfGivenDay as $news) {
            $newsTitles[] = $news->getTitle();
        }

        self::assertSame($list, implode(',', $newsTitles));
    }

    /**
     * @return News[]
     */
    protected static function generateNewsList(): array
    {
        $newsList = [];
        $data = [
            ['01', '2015-02-03 10:00', null],
            ['02', '2015-02-10 23:00', null],
            ['03', '2015-02-12 11:30', null],
            ['04', '2015-02-20 08:30', null],
            ['05', '2015-02-20 09:30', null],
            ['06', '2015-02-20 10:30', null],
            ['07', '2015-02-21 07:30', null],
            ['08', '2015-02-23 14:30', null],
            ['09', '2015-02-25 17:30', null],
            ['10', '2015-02-26 18:30', null],
            ['11', '2015-02-27 21:30', null],
            ['12', '2015-02-28 22:00', null],
            ['13', '2015-01-30 22:00', '2015-02-07 10:00'],
            ['14', '2015-01-28 22:00', '2015-03-02 11:00'],
            ['15', '2015-02-14 12:00', '2015-03-05 11:00'],
            ['16', '2015-02-19 15:00', '2015-02-22 11:00'],
            ['17', '2015-01-28 15:00', '2015-01-29 11:00'],
            ['18', '2015-03-01 15:00', '2015-03-02 11:00'],
        ];

        foreach ($data as $item) {
            $news = new News();
            $news->setTitle($item[0]);
            $news->setDatetime(new \DateTime($item[1]));

            if ($item[2] !== null) {
                $news->setEventEnd(new \DateTime($item[2]));
            }
            $newsList[] = $news;
        }

        return $newsList;
    }

    public static function newsOfADayProvider(): array
    {
        return [
            ['2015-04-01', ''],
            ['2015-02-01', '13,14'],
            ['2015-02-20', '04,05,06,14,15,16'],
            ['2015-02-21', '07,14,15,16'],
        ];
    }
}
