<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\ViewHelpers;

use GeorgRinger\Eventnews\Domain\Model\News;
use GeorgRinger\Eventnews\ViewHelpers\CalendarViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * On the day the clocks go back, an event used to disappear from the calendar.
 *
 * Extbase can hand out DateTime objects in two shapes. With the feature flag
 * extbase.consistentDateTimeHandling disabled - the default for TYPO3 13
 * instances that existed before 13.4 - DataMapper round-trips the timestamp
 * through date('c'), which yields a fixed UTC offset. With the flag enabled -
 * the default on v14 and on new v13 instances - DateTimeFactory keeps the
 * named time zone.
 *
 * Only the first shape triggered the bug, so the tests cover both.
 */
class CalendarViewHelperDaylightSavingTest extends UnitTestCase
{
    private string $originalTimeZone;

    protected function setUp(): void
    {
        parent::setUp();
        $this->originalTimeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->originalTimeZone);
        parent::tearDown();
    }

    #[Test]
    #[DataProvider('transitionDayProvider')]
    public function eventIsFoundOnTheDayTheClocksChange(string $mapping, int $hour, string $date): void
    {
        $timestamp = (new \DateTime($date . ' ' . sprintf('%02d:00:00', $hour)))->getTimestamp();

        $news = new News();
        $news->setTitle('event');
        $news->setDatetime(self::mapDateTime($mapping, $timestamp));
        $news->setEventEnd(self::mapDateTime($mapping, $timestamp));

        self::assertSame(
            ['event'],
            $this->titlesForDay([$news], $date),
            sprintf('Event at %02d:00 on %s was not found (%s mapping)', $hour, $date, $mapping)
        );
    }

    public static function transitionDayProvider(): array
    {
        $cases = [];
        foreach (['fixedOffset', 'namedZone'] as $mapping) {
            // Europe/Berlin 2025: clocks go back on 26 October at 03:00,
            // forward on 30 March at 02:00.
            foreach ([['2025-10-26', [0, 2, 3, 9, 23]], ['2025-03-30', [0, 1, 3, 9, 23]]] as [$date, $hours]) {
                foreach ($hours as $hour) {
                    $cases[sprintf('%s %s %02d:00', $mapping, $date, $hour)] = [$mapping, $hour, $date];
                }
            }
        }

        return $cases;
    }

    #[Test]
    #[DataProvider('mappingProvider')]
    public function eventSpanningTheTransitionIsFoundOnEveryDay(string $mapping): void
    {
        $news = new News();
        $news->setTitle('event');
        $news->setDatetime(self::mapDateTime($mapping, (new \DateTime('2025-10-24 09:00:00'))->getTimestamp()));
        $news->setEventEnd(self::mapDateTime($mapping, (new \DateTime('2025-10-28 18:00:00'))->getTimestamp()));

        foreach (['2025-10-24', '2025-10-25', '2025-10-26', '2025-10-27', '2025-10-28'] as $date) {
            self::assertSame(['event'], $this->titlesForDay([$news], $date), $date . ' (' . $mapping . ')');
        }

        foreach (['2025-10-23', '2025-10-29'] as $date) {
            self::assertSame([], $this->titlesForDay([$news], $date), $date . ' (' . $mapping . ')');
        }
    }

    #[Test]
    #[DataProvider('mappingProvider')]
    public function eventWithoutEndDateIsFoundOnTheTransitionDay(string $mapping): void
    {
        $news = new News();
        $news->setTitle('event');
        $news->setDatetime(self::mapDateTime($mapping, (new \DateTime('2025-10-26 09:00:00'))->getTimestamp()));

        self::assertSame(['event'], $this->titlesForDay([$news], '2025-10-26'));
        self::assertSame([], $this->titlesForDay([$news], '2025-10-27'));
    }

    public static function mappingProvider(): array
    {
        return [
            'fixed offset' => ['fixedOffset'],
            'named zone' => ['namedZone'],
        ];
    }

    /**
     * fixedOffset mirrors DataMapper::mapDateTime() with
     * extbase.consistentDateTimeHandling disabled, namedZone mirrors
     * DateTimeFactory::createFromTimestamp().
     */
    private static function mapDateTime(string $mapping, int $timestamp): \DateTime
    {
        if ($mapping === 'fixedOffset') {
            return new \DateTime(date('c', $timestamp));
        }

        return \DateTime::createFromImmutable((new \DateTimeImmutable())->setTimestamp($timestamp));
    }

    /**
     * @param News[] $newsList
     * @return string[]
     */
    private function titlesForDay(array $newsList, string $date): array
    {
        // render() builds the day this way
        $currentDay = \DateTime::createFromFormat('d-m-Y H:i:s', (new \DateTime($date))->format('d-m-Y') . ' 00:00:01');

        $method = new \ReflectionMethod(CalendarViewHelper::class, 'getNewsForDay');

        return array_map(
            static fn(News $news): string => $news->getTitle(),
            $method->invoke(new CalendarViewHelper(), $newsList, $currentDay)
        );
    }
}
