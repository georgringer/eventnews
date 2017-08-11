<?php

namespace GeorgRinger\Eventnews\Hooks;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexFormHook
{
    /**
     * Hook for 7x
     *
     * @param array $dataStructure
     * @param array $conf
     * @param array $row
     * @param string $table
     */
    public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table)
    {
        if ($table === 'tt_content' && $row['CType'] === 'list' && $row['list_type'] === 'news_pi1') {
            $file = ExtensionManagementUtility::extPath('eventnews') . 'Configuration/Flexforms/flexform_eventnews.xml';
            $content = file_get_contents($file);
            if ($content) {
                $newField = GeneralUtility::xml2array($content);
                $dataStructure['sheets']['sDEF']['ROOT']['el'] += $newField;
            }
        }
    }

    /**
     * Hook for 8x
     *
     * @param array $dataStructure
     * @param array $identifier
     * @return array
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier)
    {
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $file = ExtensionManagementUtility::extPath('eventnews') . 'Configuration/Flexforms/flexform_eventnews.xml';
            $content = file_get_contents($file);
            if ($content) {
                $newField = GeneralUtility::xml2array($content);
                $dataStructure['sheets']['sDEF']['ROOT']['el'] += $newField;
            }
        }
        return $dataStructure;
    }
}
