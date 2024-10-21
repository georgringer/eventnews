<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Domain\Model\Dto;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class Demand extends NewsDemand
{
    const EVENT_RESTRICTION_ONLY_EVENTS = 1;
    const EVENT_RESTRICTION_NO_EVENTS = 2;

    /** @var array */
    protected $locations;

    /** @var array */
    protected $organizers;

    /** @var int */
    protected $eventRestriction;

    /** @var string */
    protected $searchDateFrom;

    /** @var string */
    protected $searchDateTo;

    /** @var bool */
    protected $respectDay = false;

    public function __construct(array $settings = null)
    {
        if (is_array($settings) && isset($settings['eventRestriction'])) {
            $this->eventRestriction = $settings['eventRestriction'];
        }
    }

    /**
     * @return array
     */
    public function getOrganizers()
    {
        return $this->getNonEmptyArrayValues($this->organizers);
    }

    /**
     * @param array $organizers
     */
    public function setOrganizers($organizers)
    {
        $this->organizers = $organizers;
    }

    /**
     * @return array
     */
    public function getLocations()
    {
        return $this->getNonEmptyArrayValues($this->locations);
    }

    /**
     * @param array $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return int
     */
    public function getEventRestriction()
    {
        return (int) $this->eventRestriction;
    }

    /**
     * @param int $eventRestriction
     */
    public function setEventRestriction($eventRestriction)
    {
        $this->eventRestriction = $eventRestriction;
    }

    /**
     * @return string
     */
    public function getSearchDateTo()
    {
        return $this->searchDateTo;
    }

    /**
     * @param string $searchDateTo
     */
    public function setSearchDateTo($searchDateTo)
    {
        $this->searchDateTo = $searchDateTo;
    }

    /**
     * @return string
     */
    public function getSearchDateFrom()
    {
        return $this->searchDateFrom;
    }

    /**
     * @param string $searchDateFrom
     */
    public function setSearchDateFrom($searchDateFrom)
    {
        $this->searchDateFrom = $searchDateFrom;
    }

    /**
     * Remove empty value entries.
     *
     * @param $array
     *
     * @return array
     */
    public function getNonEmptyArrayValues($array)
    {
        $out = [];
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if (!empty($v)) {
                    $out[$k] = $v;
                }
            }
        }

        return $out;
    }

    /**
     * @return bool
     */
    public function getRespectDay(): bool
    {
        return $this->respectDay;
    }

    /**
     * @param bool $respectDay
     */
    public function setRespectDay(bool $respectDay)
    {
        $this->respectDay = $respectDay;
    }
}
