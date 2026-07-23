<?php

namespace GeorgRinger\Eventnews\Domain\Model;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * News
 */
class News extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * isEvent
     */
    protected bool $isEvent = false;

    /**
     * fullDay
     */
    protected bool $fullDay = false;

    /**
     * eventEnd
     */
    protected ?\DateTime $eventEnd = null;

    /**
     * organizer
     */
    protected ?\GeorgRinger\Eventnews\Domain\Model\Organizer $organizer = null;

    /**
     * location
     */
    protected ?\GeorgRinger\Eventnews\Domain\Model\Location $location = null;

    protected string $organizerSimple = '';

    protected string $locationSimple = '';

    /**
     * Returns the isEvent
     */
    public function getIsEvent(): bool
    {
        return $this->isEvent;
    }

    /**
     * Sets the isEvent
     */
    public function setIsEvent(bool $isEvent): void
    {
        $this->isEvent = $isEvent;
    }

    /**
     * Returns the boolean state of isEvent
     */
    public function isIsEvent(): bool
    {
        return $this->isEvent;
    }

    /**
     * Returns the fullDay
     */
    public function getFullDay(): bool
    {
        return $this->fullDay;
    }

    /**
     * Sets the fullDay
     */
    public function setFullDay(bool $fullDay): void
    {
        $this->fullDay = $fullDay;
    }

    /**
     * Returns the boolean state of fullDay
     */
    public function isFullDay(): bool
    {
        return $this->fullDay;
    }

    /**
     * Returns the eventEnd
     */
    public function getEventEnd(): ?\DateTime
    {
        return $this->eventEnd;
    }

    /**
     * Sets the eventEnd
     */
    public function setEventEnd(?\DateTime $eventEnd): void
    {
        $this->eventEnd = $eventEnd;
    }

    /**
     * Returns the organizer
     */
    public function getOrganizer(): ?\GeorgRinger\Eventnews\Domain\Model\Organizer
    {
        return $this->organizer;
    }

    /**
     * Sets the organizer
     */
    public function setOrganizer(?\GeorgRinger\Eventnews\Domain\Model\Organizer $organizer): void
    {
        $this->organizer = $organizer;
    }

    /**
     * Returns the location
     */
    public function getLocation(): ?\GeorgRinger\Eventnews\Domain\Model\Location
    {
        return $this->location;
    }

    /**
     * Sets the location
     */
    public function setLocation(?\GeorgRinger\Eventnews\Domain\Model\Location $location): void
    {
        $this->location = $location;
    }

    public function getOrganizerSimple(): string
    {
        return $this->organizerSimple;
    }

    public function setOrganizerSimple(string $organizerSimple): void
    {
        $this->organizerSimple = $organizerSimple;
    }

    public function getLocationSimple(): string
    {
        return $this->locationSimple;
    }

    public function setLocationSimple(string $locationSimple): void
    {
        $this->locationSimple = $locationSimple;
    }
}
