<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexFormHook
{

    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $dataStructure = $event->getDataStructure();
        $identifier = $event->getIdentifier();

        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === '*,eventnews_newsmonth') {
            $content = file_get_contents($this->getPath());
            if ($content) {
                $dataStructure['sheets']['extraEntryEventNews'] = GeneralUtility::xml2array($content);
            }
        }
        $event->setDataStructure($dataStructure);
    }

    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === '*,eventnews_newsmonth') {
            $content = file_get_contents($this->getPath());
            if ($content) {
                $dataStructure['sheets']['extraEntryEventNews'] = GeneralUtility::xml2array($content);
            }
        }
        return $dataStructure;
    }

    protected function getPath(): string
    {
        $file = (new Typo3Version())->getMajorVersion() >= 12 ? 'flexform_eventnews12.xml' : 'flexform_eventnews.xml';
        return ExtensionManagementUtility::extPath('eventnews') . 'Configuration/Flexforms/' . $file;
    }
}
