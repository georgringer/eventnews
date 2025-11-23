<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\EventListener\Administration;

use GeorgRinger\NewsAdministration\Event\ModifyQueryEvent;
use TYPO3\CMS\Core\Database\Connection;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class ModifyQueryEventListener
{
    public function __invoke(ModifyQueryEvent $event): void
    {
        $eventRestriction = (int)($event->getQueryParams()['eventNewsRestriction'] ?? 0);
        if ($eventRestriction === 1 || $eventRestriction === 2) {
            $queryBuilder = $event->getQueryBuilder();
            if ($eventRestriction === 1) {
                $constraints[] = $queryBuilder->expr()->eq('is_event', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT));
            } else {
                $constraints[] = $queryBuilder->expr()->eq('is_event', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT));
            }
            $queryBuilder->andWhere(...$constraints);
        }
    }
}
