<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\Domain\Repository;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The date range of the EXT:news search plugin compares a single field, so an
 * event that started before the range and ends after it is missed. See #153.
 */
class NewsSearchTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/EventsForSearch.csv');
    }

    #[Test]
    public function searchForASingleDayFindsEventsRunningOverThatDay(): void
    {
        $result = $this->searchEvents('2026-03-05', '2026-03-05');

        self::assertSame([1, 2], $this->uidsOf($result));
    }

    #[Test]
    public function searchForARangeFindsEventsOverlappingIt(): void
    {
        $result = $this->searchEvents('2026-03-08', '2026-03-08');

        self::assertSame([1], $this->uidsOf($result));
    }

    #[Test]
    public function theLastDayOfARangeCountsUntilItsEnd(): void
    {
        $result = $this->searchEvents('2026-03-08', '2026-03-09');

        self::assertSame([1, 5], $this->uidsOf($result));
    }

    #[Test]
    public function eventsOutsideTheRangeStayOut(): void
    {
        $result = $this->searchEvents('2026-03-19', '2026-03-21');

        self::assertSame([3], $this->uidsOf($result));
    }

    protected function searchEvents(string $from, string $to): QueryResultInterface
    {
        $search = new Search();
        $search->setDateField('datetime');
        $search->setMinimumDate($from);
        $search->setMaximumDate($to);

        $demand = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_ONLY_EVENTS]);
        $demand->setStoragePage('1');
        $demand->setSearch($search);

        return $this->get(NewsRepository::class)->findDemanded($demand);
    }

    protected function uidsOf(QueryResultInterface $result): array
    {
        $uids = [];
        foreach ($result as $news) {
            $uids[] = $news->getUid();
        }
        sort($uids);

        return $uids;
    }
}
