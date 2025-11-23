<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Unit\Hooks;

use GeorgRinger\Eventnews\Hooks\FormEngineHook;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\BaseTestCase;

class FormEngineHookTest extends BaseTestCase
{
    #[Test]
    #[DataProvider('fieldIsRemovedFromOutputProvider')]
    public function fieldIsRemovedFromOutput(string $table, string $field, array $row, string $out, string $expected): void
    {
        $instance = new FormEngineHook();
        $instance->getSingleField_postProcess($table, $field, $row, $out);
        self::assertEquals($expected, $out);
    }

    public static function fieldIsRemovedFromOutputProvider(): array
    {
        return [
            'valid field' => ['tx_news_domain_model_news', 'organizer', ['is_event' => 0], 'bla', ''],
            'valid field but event' => ['tx_news_domain_model_news', 'organizer_simple', ['is_event' => 1], 'bla', 'bla'],
            'other table' => ['fe_users', 'organizer_simple', ['is_event' => 0], 'bla', 'bla'],
        ];
    }
}
