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

class LocationViewHelper extends AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('locations', 'object', 'Locations');
        $this->registerArgument('news', 'object', 'News');
        $this->registerArgument('as', 'string', 'as variable', false, 'filteredLocations');
    }

    /**
     * @return string
     */
    public function render()
    {
        $locations = $this->arguments['locations'];
        $news = $this->arguments['news'];
        $as = $this->arguments['as'];

        $filteredLocations = $availableLocations = [];
        foreach ($locations as $locationItem) {
            $availableLocations[$locationItem->getUid()] = 1;
        }
        foreach ($news as $n) {
            /** @var News $n */
            $loc = $n->getLocation();
            if ($loc && isset($availableLocations[$loc->getUid()]) && !isset($filteredLocations[$loc->getUid()])) {
                $filteredLocations[$loc->getUid()] = $loc;
            }
        }
        $this->templateVariableContainer->add($as, $filteredLocations);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }
}
