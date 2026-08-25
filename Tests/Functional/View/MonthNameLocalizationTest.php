<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tests\Functional\View;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * The month browser of the calendar renders month and weekday names, which
 * have to follow the language of the site, see #81.
 */
class MonthNameLocalizationTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'georgringer/news',
        'georgringer/eventnews',
    ];

    #[Test]
    #[DataProvider('expressionProvider')]
    public function datesAreRenderedInTheLanguageOfTheSite(string $source, string $expected): void
    {
        self::assertSame($expected, trim($this->render($source, 'nl-NL')));
    }

    public static function expressionProvider(): array
    {
        return [
            // What the template uses, an ICU pattern
            'month name' => ['<f:format.date date="{date}" pattern="MMMM y" />', 'februari 2026'],
            'weekday name' => ['<f:format.date date="{date}" pattern="EEE" />', 'ma'],
            // The legacy strftime syntax the template used before, still
            // localized by the Core since 12.3, kept here so the migration is
            // provably output compatible.
            'month name, strftime syntax' => ['<f:format.date date="{date}" format="%B %Y" />', 'februari 2026'],
            'weekday name, strftime syntax' => ['<f:format.date date="{date}" format="%a" />', 'ma'],
            // The weekday header is fed an integer timestamp, not a DateTime,
            // the way CalendarViewHelper hands out day.ts
            'weekday name from a timestamp' => ['<f:format.date date="{timestamp}" pattern="EEE" />', 'ma'],
            // Purely numeric, no language involved
            'numeric date' => ['<f:format.date date="{date}" format="d.m.Y" />', '02.02.2026'],
        ];
    }

    #[Test]
    public function anotherSiteLanguageGivesAnotherMonthName(): void
    {
        $source = '<f:format.date date="{date}" pattern="MMMM y" />';

        self::assertSame('Februar 2026', trim($this->render($source, 'de-DE')));
        self::assertSame('February 2026', trim($this->render($source, 'en-US')));
    }

    protected function render(string $source, string $locale): string
    {
        $site = new Site('test', 1, [
            'base' => 'https://example.com/',
            'languages' => [
                ['languageId' => 0, 'title' => 'Test', 'locale' => $locale, 'base' => '/'],
            ],
        ]);

        $request = (new ServerRequest('https://example.com/'))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('site', $site)
            ->withAttribute('language', $site->getLanguageById(0));

        $renderingContext = $this->get(RenderingContextFactory::class)->create([], $request);
        $renderingContext->getTemplatePaths()->setTemplateSource($source);
        $date = new \DateTime('2026-02-02 10:00:00');
        $renderingContext->getVariableProvider()->add('date', $date);
        $renderingContext->getVariableProvider()->add('timestamp', $date->getTimestamp());

        return (new TemplateView($renderingContext))->render();
    }
}
