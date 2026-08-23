<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Domain\Repository;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class AbstractRepository extends Repository
{
    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @return QueryResultInterface
     */
    public function findAll()
    {
        $query = $this->getQuery();

        return $query->execute();
    }

    /**
     * @param $pidList
     * @return QueryResultInterface
     */
    public function findByStartingPoint($pidList)
    {
        $query = $this->getQuery();

        $pidList = GeneralUtility::intExplode(',', $pidList, true);
        if (!empty($pidList)) {
            return $query->matching(
                $query->logicalAnd(
                    $query->in('pid', $pidList)
                )
            )->execute();
        }

        return $query->execute();
    }

    /**
     * @return QueryInterface
     */
    protected function getQuery()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }
}
