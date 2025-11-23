<?php

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Location;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LocationTest extends BaseTestCase
{
    /**
     * @var Location
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Location();
    }

    /**
     * @test
     */
    public function setTitle(): void
    {
        $value = 'A title';
        $this->subject->setTitle($value);

        self::assertEquals($value, $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setDescription(): void
    {
        $value = 'A description';
        $this->subject->setDescription($value);

        self::assertEquals($value, $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function setLng(): void
    {
        $value = 1.2;
        $this->subject->setLng($value);

        self::assertEquals($value, $this->subject->getLng());
    }

    /**
     * @test
     */
    public function setLat(): void
    {
        $value = 2.3;
        $this->subject->setLat($value);

        self::assertEquals($value, $this->subject->getLat());
    }

    /**
     * @test
     */
    public function setLink(): void
    {
        $value = 'montagmorgen.at';
        $this->subject->setLink($value);

        self::assertEquals($value, $this->subject->getLink());
    }
}
