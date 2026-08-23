<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Location;
use GeorgRinger\Eventnews\Domain\Model\News;
use GeorgRinger\Eventnews\Domain\Model\Organizer;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class NewsTest extends UnitTestCase
{
    protected News $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new News();
    }

    #[Test]
    public function setIsEvent(): void
    {
        $this->subject->setIsEvent(true);

        self::assertTrue($this->subject->getIsEvent());
    }

    #[Test]
    public function setFullDay(): void
    {
        $this->subject->setFullDay(true);

        self::assertTrue($this->subject->getFullDay());
    }

    #[Test]
    public function setEventEnd(): void
    {
        $value = new \DateTime('2014-10-10');
        $this->subject->setEventEnd($value);

        self::assertSame($value, $this->subject->getEventEnd());
    }

    #[Test]
    public function setOrganizer(): void
    {
        $value = new Organizer();
        $value->setTitle('Organizer 1');
        $this->subject->setOrganizer($value);

        self::assertSame($value, $this->subject->getOrganizer());
    }

    #[Test]
    public function setLocation(): void
    {
        $value = new Location();
        $value->setTitle('Location1 1');
        $this->subject->setLocation($value);

        self::assertSame($value, $this->subject->getLocation());
    }
}
