<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\Tca;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Smoke tests proving that the extension's TCA is registered and that the
 * columns eventnews adds to EXT:news survive a full TCA build.
 */
class TcaTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    #[Test]
    #[DataProvider('ownTablesProvider')]
    public function tcaIsLoaded(string $table): void
    {
        self::assertArrayHasKey($table, $GLOBALS['TCA']);
        self::assertNotEmpty($GLOBALS['TCA'][$table]['columns']);
    }

    public static function ownTablesProvider(): array
    {
        return [
            'location' => ['tx_eventnews_domain_model_location'],
            'organizer' => ['tx_eventnews_domain_model_organizer'],
        ];
    }

    /**
     * Since TYPO3 13.3 the Core derives these columns from the table's 'ctrl'
     * capabilities, so the extension does not define them any more. See
     * Feature-104311-AutoCreatedSystemTCAColumns. The test guards the
     * assumption: without the auto-creation the fields placed in the 'access'
     * and 'language' palettes would silently vanish from the form.
     */
    #[Test]
    #[DataProvider('systemColumnProvider')]
    public function systemColumnsAreCreatedFromCtrl(string $table, string $column): void
    {
        self::assertArrayHasKey(
            $column,
            $GLOBALS['TCA'][$table]['columns'],
            sprintf('%s.%s was not auto-created from ctrl', $table, $column)
        );
    }

    public static function systemColumnProvider(): array
    {
        $cases = [];
        foreach (['tx_eventnews_domain_model_location', 'tx_eventnews_domain_model_organizer'] as $table) {
            foreach (['sys_language_uid', 'l10n_parent', 'l10n_diffsource', 'hidden', 'starttime', 'endtime'] as $column) {
                $cases[$table . '.' . $column] = [$table, $column];
            }
        }

        return $cases;
    }

    #[Test]
    #[DataProvider('eventColumnsProvider')]
    public function eventColumnsAreAddedToNews(string $column): void
    {
        self::assertArrayHasKey($column, $GLOBALS['TCA']['tx_news_domain_model_news']['columns']);
    }

    public static function eventColumnsProvider(): array
    {
        return [
            'is_event' => ['is_event'],
            'event_end' => ['event_end'],
            'organizer' => ['organizer'],
            'location' => ['location'],
        ];
    }

    #[Test]
    public function isEventIsShownInTheNewsFormAfterDatetime(): void
    {
        $showitem = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0']['showitem'];

        self::assertStringContainsString('is_event', $showitem);
    }
}
