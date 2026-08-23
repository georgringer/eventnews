<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Backend\FormDataProvider;

use GeorgRinger\Eventnews\Backend\FormDataProvider\EventNewsRowInitializeNew;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventNewsRowInitializeNewTest extends UnitTestCase
{
    #[Test]
    public function newNewsHasDefaultValueForIsEvent(): void
    {
        $instance = new EventNewsRowInitializeNew();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'pageTsConfig' => [
                'tx_news.' => [
                    'newRecordAsEvent' => 1,
                ],
            ],
        ];

        $result = $instance->addData($result);

        self::assertSame(1, $result['databaseRow']['is_event']);
    }
}
