<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Hooks;

use GeorgRinger\Eventnews\Hooks\IconHook;
use TYPO3\TestingFramework\Core\BaseTestCase;

class IconHookTest extends BaseTestCase
{
    /**
     * @test
     *
     * @dataProvider iconForEventNewsIsChangedProvider
     *
     * @param array       $day
     * @param string|null $returnValue
     */
    public function iconForEventNewsIsChanged(array $configuration, $returnValue): void
    {
        $instance = new IconHook();
        // @extensionScannerIgnoreLine
        $this->assertEquals($returnValue, $instance->run($configuration));
    }

    /**
     * @return array
     */
    public function iconForEventNewsIsChangedProvider(): array
    {
        return [
            'event news'          => [['row' => ['is_event' => 1]], 'ext-news-type-event'],
            'no event news'       => [['row' => ['is_event' => 0]], null],
            'wrong configuration' => [['row' => ['title' => 'a title']], null],
        ];
    }
}
