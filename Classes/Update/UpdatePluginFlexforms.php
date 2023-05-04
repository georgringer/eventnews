<?php

namespace GeorgRinger\Eventnews\Update;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdatePluginFlexforms implements \TYPO3\CMS\Install\Updates\UpgradeWizardInterface
{
    protected ConnectionPool $connectionPool;
    protected FlexFormTools $flexFormTools;
    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        ConnectionPool $connectionPool,
        FlexFormTools $flexFormTools
    ) {
        $this->logger = $logger;
        $this->connectionPool = $connectionPool;
        $this->flexFormTools = $flexFormTools;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return 'tx_eventnews_updateFlexforms';
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return 'Eventnews flexform data update';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'The tx_news plugin flexform field \'settings.eventRestriction\' was moved to its own flexform sheet. Update the flexform data in the tt_content rows accordingly.';
    }

    /**
     * @inheritDoc
     */
    public function updateNecessary(): bool
    {
        $flexForms = $this->getFlexFormsToUpdate();
        return $flexForms->valid();
    }

    /**
     * @inheritDoc
     */
    public function executeUpdate(): bool
    {
        $updateStatement = $this->getUpdateStatement();

        foreach($this->getFlexFormsToUpdate() as $uid => $flexForm) {
            $newFlexForm = $this->updateFlexForm($flexForm);
            $newFlexFormString = $this->flexFormTools->flexArray2Xml($newFlexForm, addPrologue: true);
            $updateStatement->executeStatement([$newFlexFormString, $uid]);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    protected function getFlexFormsToUpdate(): \Generator
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $queryResult = $queryBuilder
            ->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('news_pi1')),
            )
            ->executeQuery();

        while($contentRow = $queryResult->fetchAssociative()) {
            $flexForm = GeneralUtility::xml2array($contentRow['pi_flexform']);
            if (
                isset($flexForm['data']['sDEF']['lDEF']['settings.eventRestriction'])
            ) {
                $uid = $contentRow['uid'];
                $oldValue = $flexForm['data']['sDEF']['lDEF']['settings.eventRestriction']['vDEF'] ?? null;
                $newValue = $flexForm['data']['extraEntryEventNews']['lDEF']['settings.eventRestriction']['vDEF'] ?? null;
                if ($oldValue != $newValue) {
                    yield $uid => $flexForm;
                }
            }
        }
    }

    protected function getUpdateStatement(): \Doctrine\DBAL\Statement
    {
        $connection = $this->connectionPool->getConnectionForTable('tt_content');
        $queryBuilder = $connection->createQueryBuilder();
        $updateSql = $queryBuilder
            ->update('tt_content')
            ->set('pi_flexform', $queryBuilder->createPositionalParameter('flex'), false)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createPositionalParameter(0, \PDO::PARAM_INT)))
            ->getSQL();
        return $connection->prepare($updateSql);
    }

    protected function updateFlexForm(array $flexForm): array
    {
        $newFlexForm = $flexForm;
        $newFlexForm['data']['extraEntryEventNews']['lDEF']['settings.eventRestriction'] = $flexForm['data']['sDEF']['lDEF']['settings.eventRestriction'];
        unset($newFlexForm['data']['sDEF']['lDEF']['settings.eventRestriction']);
        return $newFlexForm;
    }
}