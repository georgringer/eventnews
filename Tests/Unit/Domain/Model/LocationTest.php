<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Domain\Model;

use GeorgRinger\Eventnews\Domain\Model\Location;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('addressFieldProvider')]
    public function setAddressField(string $property, string $value): void
    {
        $this->subject->{'set' . ucfirst($property)}($value);

        self::assertSame($value, $this->subject->{'get' . ucfirst($property)}());
    }

    public static function addressFieldProvider(): array
    {
        return [
            'address' => ['address', 'Kirchengasse 1'],
            'zip' => ['zip', '4020'],
            'city' => ['city', 'Linz'],
            'state' => ['state', 'Upper Austria'],
            'municipality' => ['municipality', 'Linz-Stadt'],
            'country' => ['country', 'AT'],
            'phone' => ['phone', '+43 732 123456'],
            'email' => ['email', 'office@example.org'],
        ];
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
