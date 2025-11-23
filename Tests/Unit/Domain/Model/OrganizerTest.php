<?php

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Organizer;
use TYPO3\TestingFramework\Core\BaseTestCase;

class OrganizerTest extends BaseTestCase
{
    /** @var Organizer */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new Organizer();
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
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
    public function setLink(): void
    {
        $value = 'www.typo3.org';
        $this->subject->setLink($value);

        self::assertEquals($value, $this->subject->getLink());
    }
}
