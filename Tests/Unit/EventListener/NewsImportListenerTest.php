<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\EventListener;

use GeorgRinger\Eventnews\Domain\Model\News;
use GeorgRinger\Eventnews\EventListener\NewsImportListener;
use GeorgRinger\News\Domain\Service\NewsImportService;
use GeorgRinger\News\Event\NewsImportPostHydrateEvent;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class NewsImportListenerTest extends UnitTestCase
{
    #[Test]
    public function dynamicDataIsMappedOntoTheNewsRecord(): void
    {
        $news = new News();
        $news->setTitle('A news');

        $importItem = [
            '_dynamicData' => [
                'location' => 'Example Location',
                'datetime_end' => 1581100369,
            ],
        ];

        $event = new NewsImportPostHydrateEvent(
            $this->createMock(NewsImportService::class),
            $importItem,
            $news
        );

        (new NewsImportListener())($event);

        self::assertEquals(new \DateTime('@1581100369'), $news->getEventEnd());
        self::assertSame('Example Location', $news->getLocationSimple());
    }

    #[Test]
    public function absentDynamicDataLeavesTheNewsRecordUntouched(): void
    {
        $news = new News();

        $event = new NewsImportPostHydrateEvent(
            $this->createMock(NewsImportService::class),
            ['_dynamicData' => []],
            $news
        );

        (new NewsImportListener())($event);

        self::assertNull($news->getEventEnd());
        self::assertEmpty($news->getLocationSimple());
    }
}
