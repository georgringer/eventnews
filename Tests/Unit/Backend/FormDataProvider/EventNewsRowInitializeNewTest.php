<?php
declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Backend\FormDataProvider;

use GeorgRinger\Eventnews\Backend\FormDataProvider\EventNewsRowInitializeNew;
use TYPO3\TestingFramework\Core\BaseTestCase;

class EventNewsRowInitializeNewTest extends BaseTestCase
{

    /**
     * @test
     */
    public function newNewsHasDefaultValueForIsEvent(): void
    {
        $instance = new EventNewsRowInitializeNew();

        $result = [
            'command' => 'new',
            'tableName' => 'tx_news_domain_model_news',
            'pageTsConfig' => [
                'tx_news.' => [
                    'newRecordAsEvent' => 1
                ]
            ]
        ];

        $result = $instance->addData($result);
        $this->assertEquals(1, $result['databaseRow']['is_event']);
    }
}
