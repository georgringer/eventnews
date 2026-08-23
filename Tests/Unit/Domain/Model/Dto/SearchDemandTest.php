<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model\Dto;

use GeorgRinger\Eventnews\Domain\Model\Dto\SearchDemand;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SearchDemandTest extends UnitTestCase
{
    protected SearchDemand $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new SearchDemand();
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
    public function setLocations(): void
    {
        $value = [
            4 => 4,
            5 => 5,
        ];
        $this->subject->setLocations($value);

        self::assertSame($value, $this->subject->getLocations());
    }

    #[Test]
    public function setCategories(): void
    {
        $value = [
            5 => 5,
            6 => 6,
        ];
        $this->subject->setCategories($value);

        self::assertSame($value, $this->subject->getCategories());
    }
}
