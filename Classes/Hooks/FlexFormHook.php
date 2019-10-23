<?php
declare(strict_types=1);
namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexFormHook
{
    /**
     * @param array $dataStructure
     * @param array $identifier
     * @return array
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier)
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $file = Environment::getPublicPath() . '/typo3conf/ext/eventnews/Configuration/Flexforms/flexform_eventnews.xml';
            $content = file_get_contents($file);
            if ($content) {
                $dataStructure['sheets']['extraEntryEventNews'] = GeneralUtility::xml2array($content);
            }
        }
        return $dataStructure;
    }
}
