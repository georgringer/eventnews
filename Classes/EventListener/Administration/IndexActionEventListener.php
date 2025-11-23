<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\EventListener\Administration;

use GeorgRinger\NewsAdministration\Event\AdministrationIndexActionEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class IndexActionEventListener
{
    public function __invoke(AdministrationIndexActionEvent $event)
    {
        $assignedValues = $event->getAssignedValues();
        $assignedValues['additionalHtml']['eventnews'] = $this->getHtml($assignedValues);

        $event->setAssignedValues($assignedValues);
    }

    private function getHtml(array $assignedValues): string
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:eventnews/Resources/Private/Templates/Administration/AdditionalFilter.html'));
        $standaloneView->assignMultiple($assignedValues);

        return $standaloneView->render();
    }
}
