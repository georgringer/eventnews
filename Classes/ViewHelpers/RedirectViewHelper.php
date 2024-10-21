<?php

namespace GeorgRinger\Eventnews\ViewHelpers;

/**
 * This file is part of the "eventnews" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Redirect a given url
 * <f:if condition="{news -> f:count()} == 1">
 *   <f:if condition="{demand.day}">
 *       <f:for each="{news}" as="newsItem">
 *           <events:redirect url="{n:link(newsItem:newsItem,settings:settings,uriOnly:1,configuration:{forceAbsoluteUrl:1})}" />
 *       </f:for>
 *   </f:if>
 *  </f:if>.
 */
class RedirectViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('url', 'string', '', true);
    }

    /**
     * @param array                     $arguments
     * @param \Closure                  $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return void
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        if ($arguments['url']) {
            HttpUtility::redirect($arguments['url']);
        }
    }
}
