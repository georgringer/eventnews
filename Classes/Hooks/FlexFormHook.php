<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexFormHook
{
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === '*,eventnews_newsmonth') {
            $file = ExtensionManagementUtility::extPath('eventnews') . 'Configuration/Flexforms/flexform_eventnews.xml';
            $content = file_get_contents($file);
            if ($content) {
                $dataStructure['sheets']['extraEntryEventNews'] = GeneralUtility::xml2array($content);
            }
        }
        return $dataStructure;
    }
}
