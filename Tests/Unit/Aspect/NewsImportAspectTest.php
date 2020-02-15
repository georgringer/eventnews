<?php
declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Aspect;

use GeorgRinger\Eventnews\Aspect\NewsImportAspect;
use GeorgRinger\Eventnews\Domain\Model\News;
use TYPO3\TestingFramework\Core\BaseTestCase;

class NewsImportAspectTest extends BaseTestCase
{

    /**
     * @test
     */
    public function aspectIsWorking(): void
    {
        $news = new News();
        $news->setTitle('A news');

        $date = new \DateTime('@1581100369');
        $importData = [
            '_dynamicData' => [
                'location' => 'Example Location',
                'datetime_end' => 1581100369
            ]
        ];

        $instance = new NewsImportAspect();
        $instance->postHydrate($importData, $news);

        $this->assertEquals($date, $news->getEventEnd());
        $this->assertEquals('Example Location', $news->getLocationSimple());
    }
}
