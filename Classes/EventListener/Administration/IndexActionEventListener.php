<?php

declare(strict_types=1);

namespace GeorgRinger\Eventnews\EventListener\Administration;

use GeorgRinger\NewsAdministration\Event\AdministrationIndexActionEvent;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
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
        if ((new Typo3Version())->getMajorVersion() < 14) {
            $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
            $standaloneView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:eventnews/Resources/Private/Templates/Administration/AdditionalFilter.html'));
            $standaloneView->assignMultiple($assignedValues);
            return $standaloneView->render();
        } else {
            $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
            $viewFactoryData = new ViewFactoryData(
                templateRootPaths: ['EXT:eventnews/Resources/Private/Templates'],
                partialRootPaths: ['EXT:eventnews/Resources/Private/Partials'],
                layoutRootPaths: ['EXT:eventnews/Resources/Private/Layouts'],
                request: $GLOBALS['TYPO3_REQUEST'],
            );
            $view = $viewFactory->create($viewFactoryData);
            $view->assignMultiple($assignedValues);
            return $view->render('Administration/AdditionalFilter');
        }
    }
}
