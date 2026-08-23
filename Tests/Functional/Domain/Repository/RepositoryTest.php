<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\Domain\Repository;

use GeorgRinger\Eventnews\Domain\Repository\LocationRepository;
use GeorgRinger\Eventnews\Domain\Repository\OrganizerRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Proves that the extension's services can be built from the DI container,
 * which is what breaks first when a constructor signature changes in a core
 * or EXT:news update.
 */
class RepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    #[Test]
    #[DataProvider('repositoryProvider')]
    public function repositoryCanBeInstantiated(string $className): void
    {
        self::assertInstanceOf($className, $this->get($className));
    }

    public static function repositoryProvider(): array
    {
        return [
            'location' => [LocationRepository::class],
            'organizer' => [OrganizerRepository::class],
        ];
    }
}
