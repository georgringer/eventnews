<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\Tca;

use GeorgRinger\Eventnews\Tca\CountryItemsProcFunc;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The proc func only feeds the TYPO3 13 variant of the location's country
 * field, but it does not depend on the core version itself, so it is tested
 * on both.
 */
class CountryItemsProcFuncTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    #[Test]
    public function everyCountryIsAddedWithItsIsoCodeAsValue(): void
    {
        $config = ['items' => [['label' => '', 'value' => '']]];
        (new CountryItemsProcFunc())->populate($config);

        $values = array_column($config['items'], 'value');

        self::assertGreaterThan(200, count($values));
        self::assertContains('AT', $values);
        self::assertContains('DK', $values);
        // The empty item the TCA defines is kept, the proc func only appends.
        self::assertSame('', $values[0]);
    }

    #[Test]
    public function countriesAreSortedByTheirLocalizedName(): void
    {
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->create('default');

        $config = ['items' => []];
        (new CountryItemsProcFunc())->populate($config);

        $names = array_map(
            static fn(array $item): string => $GLOBALS['LANG']->sL($item['label']),
            $config['items']
        );

        $sorted = $names;
        usort($sorted, strcoll(...));

        self::assertSame($sorted, $names);
    }

    #[Test]
    public function labelsAreLanguageFileReferencesTheFormEngineResolves(): void
    {
        $config = ['items' => []];
        (new CountryItemsProcFunc())->populate($config);

        $labels = array_column($config['items'], 'label');

        self::assertContains('LLL:EXT:core/Resources/Private/Language/Iso/countries.xlf:AT.name', $labels);
    }
}
