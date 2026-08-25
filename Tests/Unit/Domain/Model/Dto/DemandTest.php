<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model\Dto;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\News\Domain\Model\Dto\Search;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class DemandTest extends UnitTestCase
{
    protected Demand $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Demand();
    }

    #[Test]
    public function setOrganizers(): void
    {
        $value = [
            3 => 3,
            4 => 4,
        ];
        $this->subject->setOrganizers($value);

        self::assertSame($value, $this->subject->getOrganizers());
    }

    #[Test]
    public function setLocationsDropsEmptyValues(): void
    {
        $value = [
            4 => 4,
            5 => 5,
            6 => null,
        ];
        $valueCleaned = [
            4 => 4,
            5 => 5,
        ];
        $this->subject->setLocations($value);

        self::assertSame($valueCleaned, $this->subject->getLocations());
    }

    #[Test]
    public function setSearchMovesTheDateRangeOffAnEventSearch(): void
    {
        $search = $this->searchWithDateRange();
        $subject = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_ONLY_EVENTS]);

        $subject->setSearch($search);

        self::assertSame('2026-03-08', $subject->getSearchDateFrom());
        self::assertSame('2026-03-09', $subject->getSearchDateTo());
        self::assertSame('', $subject->getSearch()->getMinimumDate());
        self::assertSame('', $subject->getSearch()->getMaximumDate());
    }

    #[Test]
    public function setSearchLeavesTheCallersSearchObjectAlone(): void
    {
        $search = $this->searchWithDateRange();
        $subject = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_ONLY_EVENTS]);

        $subject->setSearch($search);

        self::assertSame('2026-03-08', $search->getMinimumDate());
        self::assertSame('2026-03-09', $search->getMaximumDate());
    }

    #[Test]
    public function setSearchKeepsTheDateRangeWhenEventsAreNotTheOnlyResult(): void
    {
        $search = $this->searchWithDateRange();
        $subject = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_NO_EVENTS]);

        $subject->setSearch($search);

        self::assertSame($search, $subject->getSearch());
        self::assertNull($subject->getSearchDateFrom());
    }

    /**
     * Only 'datetime' has an event end to pair up with, any other date field
     * is left to the search plugin.
     */
    #[Test]
    public function setSearchKeepsTheDateRangeOfAnotherDateField(): void
    {
        $search = $this->searchWithDateRange();
        $search->setDateField('archive');
        $subject = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_ONLY_EVENTS]);

        $subject->setSearch($search);

        self::assertSame($search, $subject->getSearch());
        self::assertNull($subject->getSearchDateFrom());
    }

    #[Test]
    public function setSearchAcceptsNoSearchObject(): void
    {
        $subject = new Demand(['eventRestriction' => Demand::EVENT_RESTRICTION_ONLY_EVENTS]);

        $subject->setSearch(null);

        self::assertNull($subject->getSearch());
    }

    protected function searchWithDateRange(): Search
    {
        $search = new Search();
        $search->setDateField('datetime');
        $search->setMinimumDate('2026-03-08');
        $search->setMaximumDate('2026-03-09');

        return $search;
    }
}
