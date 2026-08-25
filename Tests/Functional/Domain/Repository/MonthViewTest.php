<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\Domain\Repository;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The month and year range of EXT:news matches on the start date alone, so an
 * event running from January to October is only listed in January. See #198.
 */
class MonthViewTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/MonthView.csv');
    }

    #[Test]
    #[DataProvider('monthProvider')]
    public function anEventIsListedInEveryMonthItCovers(int $month): void
    {
        $result = $this->findForMonth(Demand::EVENT_RESTRICTION_ONLY_EVENTS, $month);

        self::assertSame([10], $this->uidsOf($result));
    }

    #[Test]
    #[DataProvider('monthProvider')]
    public function anEventIsListedInEveryMonthItCoversWithoutARestriction(int $month): void
    {
        $result = $this->findForMonth(3, $month);

        self::assertContains(10, $this->uidsOf($result));
    }

    public static function monthProvider(): array
    {
        $cases = [];
        foreach (range(1, 10) as $month) {
            $cases['month ' . $month] = [$month];
        }

        return $cases;
    }

    #[Test]
    public function newsRecordsStillMatchTheMonthTheyBelongTo(): void
    {
        self::assertSame([10, 11], $this->uidsOf($this->findForMonth(3, 5)));
        self::assertSame([10, 12], $this->uidsOf($this->findForMonth(3, 1)));
    }

    #[Test]
    public function aListWithoutEventsIsUntouched(): void
    {
        self::assertSame([11], $this->uidsOf($this->findForMonth(Demand::EVENT_RESTRICTION_NO_EVENTS, 5)));
        self::assertSame([12], $this->uidsOf($this->findForMonth(Demand::EVENT_RESTRICTION_NO_EVENTS, 1)));
    }

    #[Test]
    public function aMonthTheEventDoesNotCoverStaysEmpty(): void
    {
        self::assertSame([], $this->uidsOf($this->findForMonth(Demand::EVENT_RESTRICTION_ONLY_EVENTS, 11)));
    }

    protected function findForMonth(int $eventRestriction, int $month): iterable
    {
        $demand = new Demand(['eventRestriction' => $eventRestriction]);
        $demand->setStoragePage('1');
        $demand->setDateField('datetime');
        $demand->setYear(2026);
        $demand->setMonth($month);
        $demand->setDay(0);
        $demand->setRespectDay(true);

        return $this->get(NewsRepository::class)->findDemanded($demand);
    }

    protected function uidsOf(iterable $result): array
    {
        $uids = [];
        foreach ($result as $news) {
            $uids[] = $news->getUid();
        }
        sort($uids);

        return $uids;
    }
}
