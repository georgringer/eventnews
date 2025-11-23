<?php

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use TYPO3\TestingFramework\Core\BaseTestCase;

class DemandTest extends BaseTestCase
{
    /** @var Demand */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Demand();
    }

    /**
     * @test
     */
    public function setOrganizer(): void
    {
        $value = [
            3 => 3,
            4 => 4,
        ];
        $this->subject->setOrganizers($value);

        self::assertEquals($value, $this->subject->getOrganizers());
    }

    /**
     * @test
     */
    public function setLocation(): void
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

        self::assertEquals($valueCleaned, $this->subject->getLocations());
    }
}
