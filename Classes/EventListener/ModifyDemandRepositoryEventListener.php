<?php

namespace GeorgRinger\Eventnews\EventListener;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Event\ModifyDemandRepositoryEvent;
use GeorgRinger\News\Utility\ConstraintHelper;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class ModifyDemandRepositoryEventListener
{
    public function __invoke(ModifyDemandRepositoryEvent $event): void
    {
        if (!($event->getDemand() instanceof Demand)) {
            return;
        }
        $queryType = $event->getQuery()->getType();

        if ($queryType !== News::class && !is_subclass_of($queryType, News::class)) {
            return;
        }

        $constraints = $event->getConstraints();

        $this->updateEventConstraints(
            $event->getDemand(),
            $event->getQuery(),
            $constraints
        );
        $event->setConstraints($constraints);
    }

    protected function updateEventConstraints(
        DemandInterface $demand,
        QueryInterface $query,
        array &$constraints
    ): void {
        $eventRestriction = $demand->getEventRestriction();

        /** @var QueryInterface $query */
        if ($eventRestriction === Demand::EVENT_RESTRICTION_NO_EVENTS) {
            $constraints[] = $query->equals('isEvent', 0);
            return;
        }

        $onlyEvents = $eventRestriction === Demand::EVENT_RESTRICTION_ONLY_EVENTS;

        if ($onlyEvents) {
            // reset datetime constraint
            unset($constraints['datetime']);
        }

        // Events can show up in the result, so the month or year range has to
        // be paired with the event end. EXT:news matches on the start date
        // alone, which lists an event running from January to October in
        // January only, see #198. Records with no end date still match through
        // the plain range, so plain news is unaffected.
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
            $constraints['datetime'] = $query->logicalOr(...$dateConstraints);
        }

        if (!$onlyEvents) {
            return;
        }

        $constraints[] = $query->equals('isEvent', 1);
        $organizers = $demand->getOrganizers();
        if (!empty($organizers)) {
            $constraints[] = $query->in('organizer', $organizers);
        }

        $locations = $demand->getLocations();
        if (!empty($locations)) {
            $constraints[] = $query->in('location', $locations);
        }

        // Time start
        $convertedDateStart = strtotime($demand->getSearchDateFrom() ?? '');
        if (!$convertedDateStart) {
            $convertedDateStart = PHP_INT_MIN;
        }
        // Time end
        $convertedDateEnd = strtotime($demand->getSearchDateTo() ?? '');
        if ($convertedDateEnd) {
            // The date names a whole day, so the range runs to its end,
            // just like the month and year constraint above.
            $convertedDateEnd += 86399;
        } else {
            $convertedDateEnd = PHP_INT_MAX;
        }
        $dateConstraints = $this->getDateConstraint($query, 'datetime', $convertedDateStart, $convertedDateEnd);
        $constraints['datetimeSearch'] = $query->logicalOr(...$dateConstraints);

        // Time restriction to include events with startdate in the past AND enddate in the future!
        if ($demand->getTimeRestriction()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionLow($demand->getTimeRestriction());
            $constraints['timeRestrictionGreater'] = $query->logicalOr(
                $query->greaterThanOrEqual('eventEnd', $timeLimit),
                $query->greaterThanOrEqual('datetime', $timeLimit)
            );
        }
    }

    /**
     * @param QueryInterface $query
     * @param string $dateField
     * @param int $begin
     * @param int $end
     * @return array
     */
    protected function getDateConstraint(QueryInterface $query, $dateField, $begin, $end)
    {
        $eventsWithNoEndDate = [
            $query->logicalAnd(
                $query->greaterThanOrEqual($dateField, $begin),
                $query->lessThanOrEqual($dateField, $end)
            ),
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
            ),
        ];

        $dateConstraints = [
            $query->logicalAnd(...$eventsWithNoEndDate),
            $query->logicalOr(...$eventsWithEndDate),
        ];
        return $dateConstraints;
    }
}
