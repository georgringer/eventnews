<?php

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Dto\SearchDemand;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SearchDemandTest extends BaseTestCase
{
    /** @var SearchDemand */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new SearchDemand();
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
        ];

        $this->subject->setLocations($value);

        self::assertEquals($value, $this->subject->getLocations());
    }

    /**
     * @test
     */
    public function setCategory(): void
    {
        $value = [
            5 => 5,
            6 => 6,
        ];

        $this->subject->setCategories($value);

        self::assertEquals($value, $this->subject->getCategories());
    }
}
