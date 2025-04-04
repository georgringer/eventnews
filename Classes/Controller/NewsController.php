<?php

namespace GeorgRinger\Eventnews\Controller;

use GeorgRinger\Eventnews\Domain\Model\Dto\Demand;
use GeorgRinger\Eventnews\Domain\Model\Dto\SearchDemand;
use GeorgRinger\Eventnews\Domain\Repository\LocationRepository;
use GeorgRinger\Eventnews\Domain\Repository\OrganizerRepository;
use GeorgRinger\Eventnews\Event\NewsMonthActionEvent;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class NewsController extends \GeorgRinger\News\Controller\NewsController
{
    const SIGNAL_NEWS_MONTH_ACTION = 'monthAction';

    protected LocationRepository $locationRepository;
    protected OrganizerRepository $organizerRepository;

    /**
     * Month view
     *
     * @param SearchDemand $search
     * @param array $overwriteDemand
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("search")
     */
    public function monthAction(
        ?SearchDemand $search = null,
        ?array $overwriteDemand = null
    ): ResponseInterface
    {
        $demand = $this->getDemand($search, $overwriteDemand);
        $newsRecordsWithDaySupport = $this->newsRepository->findDemanded($demand);
        $demand->setRespectDay(false);
        $newsRecordsWithNoDaySupport = $this->newsRepository->findDemanded($demand);
        $categories = GeneralUtility::trimExplode(',', $this->settings['categories'] ?? [], true);

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->categoryRepository;
        $categoryRepository->setDefaultOrderings(
            [
                'title' => QueryInterface::ORDER_ASCENDING,
            ]
        );

        $tagRepository = $this->tagRepository;

        $organizerPidList = $this->settings['startingpointOrganizer'] ?? $this->settings['startingpoint'];
        $locationPidList = $this->settings['startingpointLocation'] ?? $this->settings['startingpoint'];

        $tsfe = $this->request->getAttribute('frontend.controller');

        $assignedValues = [
            'search' => $search,
            'news' => $newsRecordsWithDaySupport,
            'newsWithNoDaySupport' => $newsRecordsWithNoDaySupport,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            // @extensionScannerIgnoreLine
            'currentPageId' => $tsfe->id,
            'allOrganizers' => $this->organizerRepository->findByStartingPoint($organizerPidList),
            'allLocations' => $this->locationRepository->findByStartingPoint($locationPidList),
            'allCategories' => empty($categories) ? [] : $categoryRepository->findByIdList($categories),
            'allTags' => empty($this->settings['tags']) ? [] : $tagRepository->findByIdList(explode(',', $this->settings['tags'])),
            'previousMonthData' => $this->getDateConfig($demand, '-1 month'),
            'nextMonthData' => $this->getDateConfig($demand, '+1 month'),
            'currentMonthData' => $this->getDateConfig($demand),
        ];

        $event = $this->eventDispatcher->dispatch(new NewsMonthActionEvent($this, $assignedValues));
        $this->view->assignMultiple($event->getAssignedValues());
        return $this->htmlResponse();
    }

    protected function getDemand(
        ?SearchDemand $search = null,
        ?array $overwriteDemand = null
    ): Demand
    {
        /** @var Demand $demand */
        $demand = $this->createDemandObjectFromSettings($this->settings,
            Demand::class);
        if (is_array($overwriteDemand) && !empty($overwriteDemand)) {
            $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
        }
        if (!$demand->getMonth()) {
            $demand->setMonth(date('n'));
        }
        if (!$demand->getYear()) {
            $demand->setYear(date('Y'));
        }

        $demand->setDay((int)($overwriteDemand['day'] ?? 0));
        $demand->setRespectDay(true);

        if ($search !== null) {
            $validCategories = [];
            foreach ((array)$search->getCategories() as $cat) {
                if ($cat) {
                    $validCategories[] = $cat;
                }
            }
            if (!empty($validCategories)) {
                $demand->setCategories($validCategories);
            }
            $demand->setLocations($search->getLocations());
            $demand->setOrganizers($search->getOrganizers());
            $demand->setSearchDateFrom($search->getSearchDateFrom());
            $demand->setSearchDateTo($search->getSearchDateTo());
            $demand->setTags(implode(',', (array)$search->getTags()));
        }
        return $demand;
    }

    /**
     * Get a date configuration of the given time offset
     */
    protected function getDateConfig($demand, string $timeString = ''): array
    {
        $date = \DateTime::createFromFormat('d.m.Y', sprintf('1.%s.%s', $demand->getMonth(), $demand->getYear()));
        if (!empty($timeString)) {
            $date->modify($timeString);
        }
        return [
            'date' => $date,
            'month' => $date->format('n'),
            'year' => $date->format('Y'),
        ];
    }

    public function injectLocationRepository(LocationRepository $locationRepository): void
    {
        $this->locationRepository = $locationRepository;
    }

    public function injectOrganizerRepository(OrganizerRepository $organizerRepository): void
    {
        $this->organizerRepository = $organizerRepository;
    }
}
