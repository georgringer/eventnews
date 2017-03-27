<?php

namespace GeorgRinger\Eventnews\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class DayCompareViewHelper extends AbstractConditionViewHelper {


	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument(
			'newsItem',
			'\GeorgRinger\News\Domain\Model\News',
			'News object'
		);

		$this->registerArgument(
			'demand',
			'\GeorgRinger\Eventnews\Domain\Model\Dto\Demand',
			'Demand object'
		);
	}

	/**
	 * @return mixed
	 */
	public function render() {

		/** @var \GeorgRinger\News\Domain\Model\News $newsItem */
		$newsItem = $this->arguments['$newsItem'];

		/** @var \GeorgRinger\Eventnews\Domain\Model\Dto\Demand $demand */
		$demand = $this->arguments['$demand'];

		$currentDay = \DateTime::createFromFormat('d-m-Y H:i:s', sprintf(
			'%s-%s-%s 00:00:01', $demand->getDay(), $demand->getMonth(), $demand->getYear()));

		$found = FALSE;
		if ($demand->getDay() > 0) {
			$newsBeginDate = $newsItem->getDatetime()->format('Y-m-d');
			$day = date('Y-m-d', $currentDay->getTimestamp());

			if ($newsItem->getEventEnd() == 0) {
				if ($newsBeginDate === $day) {
					$found = TRUE;
				}
			} else {
				$newsEndDate = $newsItem->getEventEnd();
				$newsEndDate->setTime(23, 59, 59);
				$newsBeginDate = $newsItem->getDatetime();
				$newsBeginDate->setTime(0, 0);
				$currentDay->setTime(0, 0);

				if ($newsBeginDate <= $currentDay && $newsEndDate >= $currentDay) {
					$found = TRUE;
				}
			}
		}

		if ($found) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}


}
