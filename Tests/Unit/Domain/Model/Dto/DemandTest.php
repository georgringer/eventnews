<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model\Dto;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
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
}
