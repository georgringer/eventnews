<?php

namespace GeorgRinger\Eventnews\ViewHelpers\Filter;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\Eventnews\Domain\Model\News;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class OrganizerViewHelper extends AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('organizers', 'object', 'Organizers');
        $this->registerArgument('news', 'object', 'News');
        $this->registerArgument('as', 'string', 'as variable', false, 'filteredOrganizers');
    }

    /**
     * @return string
     */
    public function render()
    {
        $organizers = $this->arguments['organizers'];
        $news = $this->arguments['news'];
        $as = $this->arguments['as'];
        $filteredOrganizers = $availableOrganizers = [];
        foreach ($organizers as $organizerItem) {
            $availableOrganizers[$organizerItem->getUid()] = 1;
        }
        foreach ($news as $n) {
            /** @var News $n */
            $loc = $n->getOrganizer();
            if ($loc && isset($availableOrganizers[$loc->getUid()]) && !isset($filteredOrganizers[$loc->getUid()])) {
                $filteredOrganizers[$loc->getUid()] = $loc;
            }
        }
        $this->templateVariableContainer->add($as, $filteredOrganizers);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }
}
