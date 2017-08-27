<?php

namespace GeorgRinger\Eventnews\Domain\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class AbstractRepository extends Repository
{

    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll()
    {
        $query = $this->getQuery();

        return $query->execute();
    }

    /**
     * @param $pidList
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByStartingPoint($pidList)
    {
        $query = $this->getQuery();

        $pidList = GeneralUtility::intExplode(',', $pidList, true);
        if (!empty($pidList)) {
            return $query->matching(
                $query->logicalAnd(
                    $query->in('pid', $pidList)
                ))->execute();
        }

        return $query->execute();
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function getQuery()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }
}
