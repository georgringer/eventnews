<?php

namespace GeorgRinger\Eventnews\Hooks;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class AbstractDemandedRepository
{

    /**
     * Modify the constraints used in the query
     *
     * @param array $params
     * @return void
     */
    public function modify(array $params)
    {
        if (get_class($params['demand']) !== Demand::class || $params['query']->getType() !== News::class) {
            return;
        }

        $this->updateEventConstraints(
            $params['demand'],
            $params['respectEnableFields'],
            $params['query'],
            $params['constraints']
        );
    }

    /**
     * Update the main event constraints
     *
     * @param Demand $demand
     * @param bool $respectEnableFields
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param array $constraints
     * @return void
     */
    protected function updateEventConstraints(
        Demand $demand,
        $respectEnableFields,
        \TYPO3\CMS\Extbase\Persistence\QueryInterface $query,
        array &$constraints
    ) {
        $eventRestriction = $demand->getEventRestriction();
        $eventFilterByLocation = $demand->getEventFilterByLocation();

        /** @var QueryInterface $query */
        if ($eventRestriction === Demand::EVENT_RESTRICTION_NO_EVENTS) {
            $constraints[] = $query->equals('isEvent', 0);
        } elseif ($eventRestriction === Demand::EVENT_RESTRICTION_ONLY_EVENTS) {
            // reset datetime constraint
            unset($constraints['datetime']);
            $constraints[] = $query->equals('isEvent', 1);

            if ($demand->getYear()) {
                $dateField = $demand->getDateField();
                if (!$dateField) {
                    $dateField = 'datetime';
                }

                if ($demand->getMonth() > 0) {
                    if ($demand->getRespectDay() && $demand->getDay() > 0) {
                        $begin = mktime(0, 0, 0, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                        $end = mktime(23, 59, 59, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                    } else {
                        $begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
                        $end = mktime(23, 59, 59, ($demand->getMonth() + 1), 0, $demand->getYear());
                    }
                } else {
                    $begin = mktime(0, 0, 0, 1, 1, $demand->getYear());
                    $end = mktime(23, 59, 59, 12, 31, $demand->getYear());
                }

                $dateConstraints = $this->getDateConstraint($query, $dateField, $begin, $end);
                $constraints['datetime'] = $query->logicalOr($dateConstraints);
            }

            $organizers = $demand->getOrganizers();
            if (!empty($organizers)) {
                $constraints[] = $query->in('organizer', $organizers);
            }

            $locations = $demand->getLocations();
            if (!empty($locations)) {
                $constraints[] = $query->in('location', $locations);
            }

            if (!empty($eventFilterByLocation)) {
                $constraints[] = $query->equals('location', $eventFilterByLocation);
            }

            // Time start
            $convertedDateStart = strtotime($demand->getSearchDateFrom());
            if (!$convertedDateStart) {
                $convertedDateStart = PHP_INT_MIN;
            }
            // Time end
            $convertedDateEnd = strtotime($demand->getSearchDateTo());
            if ($convertedDateEnd) {
                $convertedDateEnd += 86350;
            } else {
                $convertedDateEnd = PHP_INT_MAX;
            }
            $dateConstraints = $this->getDateConstraint($query, 'datetime', $convertedDateStart, $convertedDateEnd);
            $constraints['datetimeSearch'] = $query->logicalOr($dateConstraints);

            // Time restriction to include events with startdate in the past AND enddate in the future!
            if ($demand->getTimeRestriction()) {
                $timeLimit = \GeorgRinger\News\Utility\ConstraintHelper::getTimeRestrictionLow($demand->getTimeRestriction());
                $constraints['timeRestrictionGreater'] = $query->logicalOr(
                    $query->greaterThanOrEqual('eventEnd', $timeLimit),
                    $query->greaterThanOrEqual('datetime', $timeLimit)
                );
            }
        }
    }

    /**
     * @param QueryInterface $query
     * @param string $dateField
     * @param int $begin
     * @param int $end
     * @return array
     */
    protected function getDateConstraint(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, $dateField, $begin, $end)
    {
        $eventsWithNoEndDate = [
            $query->logicalAnd(
                $query->greaterThanOrEqual($dateField, $begin),
                $query->lessThanOrEqual($dateField, $end)
            )
        ];

        $eventsWithEndDate = [
            // event inside a month, e.g. 3.3 - 8.3
            $query->logicalAnd(
                $query->greaterThanOrEqual('datetime', $begin),
                $query->lessThanOrEqual('datetime', $end),
                $query->lessThanOrEqual('eventEnd', $end)
            ),
            // event expanded from month before to month after
            $query->logicalAnd(
                $query->lessThanOrEqual($dateField, $begin),
                $query->greaterThanOrEqual('eventEnd', $end)
            ),
            // event from month before to mid of month
            $query->logicalAnd(
                $query->lessThanOrEqual($dateField, $begin),
                $query->greaterThanOrEqual('eventEnd', $begin)
            ),
            // event from mid month to next month
            $query->logicalAnd(
                $query->lessThanOrEqual($dateField, $end),
                $query->greaterThanOrEqual('eventEnd', $end)
            )
        ];

        $dateConstraints = [
            $query->logicalAnd($eventsWithNoEndDate),
            $query->logicalOr($eventsWithEndDate)
        ];
        return $dateConstraints;
    }
}
