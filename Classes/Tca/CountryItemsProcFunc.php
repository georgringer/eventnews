<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Tca;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Country\CountryProvider;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Fills a select field with every country known to the Core Country API,
 * storing the ISO 3166-1 alpha-2 code. Only used on TYPO3 13, where the TCA
 * type "country" does not exist yet - see the version switch in
 * Configuration/TCA/tx_eventnews_domain_model_location.php.
 */
final class CountryItemsProcFunc
{
    public function populate(array &$config): void
    {
        $languageService = $GLOBALS['LANG'] ?? null;

        $items = [];
        foreach (GeneralUtility::makeInstance(CountryProvider::class)->getAll() as $country) {
            $label = $country->getLocalizedNameLabel();
            $items[] = [
                'label' => $label,
                'value' => $country->getAlpha2IsoCode(),
                // The label is a LLL reference the FormEngine resolves later on,
                // so sorting needs the resolved name, not the reference itself.
                'sortBy' => $languageService instanceof LanguageService
                    ? $languageService->sL($label)
                    : $country->getName(),
            ];
        }

        usort($items, static fn(array $a, array $b): int => strcoll($a['sortBy'], $b['sortBy']));

        foreach ($items as $item) {
            $config['items'][] = [
                'label' => $item['label'],
                'value' => $item['value'],
            ];
        }
    }
}
