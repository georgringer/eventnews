<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Hooks;

use GeorgRinger\Eventnews\Hooks\FormEngineHook;
use TYPO3\TestingFramework\Core\BaseTestCase;

class FormEngineHookTest extends BaseTestCase
{
    /**
     * @test
     *
     * @dataProvider fieldIsRemovedFromOutputProvider
     *
     * @param string $table
     * @param string $field
     * @param array  $row
     * @param string $out
     * @param string $expected
     */
    public function fieldIsRemovedFromOutput(string $table, string $field, array $row, string $out, string $expected): void
    {
        $instance = new FormEngineHook();
        $instance->getSingleField_postProcess($table, $field, $row, $out);
        $this->assertEquals($expected, $out);
    }

    /**
     * @return array
     */
    public function fieldIsRemovedFromOutputProvider(): array
    {
        return [
            'valid field'           => ['tx_news_domain_model_news', 'organizer', ['is_event' => 0], 'bla', ''],
            'valid field but event' => ['tx_news_domain_model_news', 'organizer_simple', ['is_event' => 1], 'bla', 'bla'],
            'other table'           => ['fe_users', 'organizer_simple', ['is_event' => 0], 'bla', 'bla'],
        ];
    }
}
