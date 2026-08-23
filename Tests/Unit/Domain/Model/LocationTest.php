<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Location;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LocationTest extends UnitTestCase
{
    protected Location $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Location();
    }

    #[Test]
    public function setTitle(): void
    {
        $value = 'A title';
        $this->subject->setTitle($value);

        self::assertSame($value, $this->subject->getTitle());
    }

    #[Test]
    public function setDescription(): void
    {
        $value = 'A description';
        $this->subject->setDescription($value);

        self::assertSame($value, $this->subject->getDescription());
    }

    #[Test]
    public function setLng(): void
    {
        $value = 1.2;
        $this->subject->setLng($value);

        self::assertSame($value, $this->subject->getLng());
    }

    #[Test]
    public function setLat(): void
    {
        $value = 2.3;
        $this->subject->setLat($value);

        self::assertSame($value, $this->subject->getLat());
    }

    #[Test]
    public function setLink(): void
    {
        $value = 'montagmorgen.at';
        $this->subject->setLink($value);

        self::assertSame($value, $this->subject->getLink());
    }
}
