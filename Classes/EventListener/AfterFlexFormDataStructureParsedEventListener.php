<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\EventListener;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AfterFlexFormDataStructureParsedEventListener
{
    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $dataStructure = $event->getDataStructure();
        $identifier = $event->getIdentifier();

        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $this->isActiveOnKey($identifier['dataStructureKey'])) {
            $content = file_get_contents(ExtensionManagementUtility::extPath('eventnews') . 'Configuration/Flexforms/flexform_eventnews.xml');
            if ($content) {
                $dataStructure['sheets']['extraEntryEventNews'] = GeneralUtility::xml2array($content);
            }
        }
        $event->setDataStructure($dataStructure);
    }

    private function isActiveOnKey(string $dataStructureKey): bool
    {
        return $dataStructureKey === 'eventnews_newsmonth';
    }
}
