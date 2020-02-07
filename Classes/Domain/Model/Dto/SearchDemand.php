<?php
declare(strict_types=1);
namespace GeorgRinger\Eventnews\Domain\Model\Dto;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class SearchDemand
{

    /** @var array */
    protected $locations;

    /** @var array */
    protected $organizers;

    /** @var array */
    protected $categories;

    /** @var array */
    protected $tags;

    /** @var string */
    protected $searchDateFrom;

    /** @var string */
    protected $searchDateTo;

    /**
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param array $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return array
     */
    public function getOrganizers()
    {
        return $this->organizers;
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
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
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
}
