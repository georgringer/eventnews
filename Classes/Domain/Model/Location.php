<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\Domain\Model;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Location.
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title.
     *
     * @var string
     */
    protected $title = '';

    /**
     * description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * lat.
     *
     * @var float
     */
    protected $lat = 0.0;

    /**
     * lng.
     *
     * @var float
     */
    protected $lng = 0.0;

    /**
     * link.
     *
     * @var string
     */
    protected $link = '';

    /**
     * Returns the title.
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description.
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description.
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the lat.
     *
     * @return float $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Sets the lat.
     *
     * @param float $lat
     *
     * @return void
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Returns the lng.
     *
     * @return float $lng
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Sets the lng.
     *
     * @param float $lng
     *
     * @return void
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Returns the link.
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string $link
     *
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
}
