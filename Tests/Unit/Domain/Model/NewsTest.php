<?php

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Location;
use GeorgRinger\Eventnews\Domain\Model\News;
use GeorgRinger\Eventnews\Domain\Model\Organizer;
use TYPO3\TestingFramework\Core\BaseTestCase;

class NewsTest extends BaseTestCase
{
    /**
     * @var News
     */
    protected $subject = null;

    protected function setUp(): void
    {
        $this->subject = new News();
    }

    /**
     * @test
     */
    public function setIsEvent(): void
    {
        $value = true;
        $this->subject->setIsEvent($value);

        $this->assertEquals($value, $this->subject->getIsEvent());
    }

    /**
     * @test
     */
    public function setFullDay(): void
    {
        $value = true;
        $this->subject->setFullDay($value);

        $this->assertEquals($value, $this->subject->getFullDay());
    }

    /**
     * @test
     */
    public function setEventEnd(): void
    {
        $value = new \DateTime('2014-10-10');
        $this->subject->setEventEnd($value);

        $this->assertEquals($value, $this->subject->getEventEnd());
    }

    /**
     * @test
     */
    public function setOrganizer(): void
    {
        $value = new Organizer();
        $value->setTitle('Organizer 1');
        $this->subject->setOrganizer($value);

        $this->assertEquals($value, $this->subject->getOrganizer());
    }

    /**
     * @test
     */
    public function setLocation(): void
    {
        $value = new Location();
        $value->setTitle('Location1 1');
        $this->subject->setLocation($value);

        $this->assertEquals($value, $this->subject->getLocation());
    }
}
