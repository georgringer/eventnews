<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Organizer;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class OrganizerTest extends UnitTestCase
{
    protected Organizer $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Organizer();
    }

    #[Test]
    public function setTitleForStringSetsTitle(): void
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
    public function setLink(): void
    {
        $value = 'www.typo3.org';
        $this->subject->setLink($value);

        self::assertSame($value, $this->subject->getLink());
    }
}
