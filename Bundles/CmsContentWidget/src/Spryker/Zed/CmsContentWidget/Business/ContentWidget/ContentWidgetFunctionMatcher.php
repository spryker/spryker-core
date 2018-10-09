<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business\ContentWidget;

use Generated\Shared\Transfer\CmsContentWidgetFunctionsTransfer;
use Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer;

class ContentWidgetFunctionMatcher implements ContentWidgetFunctionMatcherInterface
{
    public const TWIG_FUNCTION_WITH_PARAMETER_REGEXP = '/{{(?:\s)?(?:&nbsp;)?([a-z_-]+)\(\[?(.*?)\]?\)(?:\s)?(?:&nbsp;)?}}/i';
    public const TRIM_WHITELIST = "'\" \t\n\r\v";

    /**
     * @param string $content
     *
     * @return \Generated\Shared\Transfer\CmsContentWidgetFunctionsTransfer
     */
    public function extractTwigFunctions($content)
    {
        $cmsContentWidgetFunctions = new CmsContentWidgetFunctionsTransfer();
        foreach ($this->matchFunctions($content) as $functionMatch) {
            if (!$this->assertRequiredProperties($functionMatch)) {
                continue;
            }

            $cmsContentWidgetFunction = new CmsContentWidgetFunctionTransfer();
            $cmsContentWidgetFunction->setFunctionName($this->extractFunctionName($functionMatch));
            $cmsContentWidgetFunction->setParameters($this->extractFunctionParameters($functionMatch));

            $cmsContentWidgetFunctions->addCmsContentWidgetFunction($cmsContentWidgetFunction);
        }

        return $cmsContentWidgetFunctions;
    }

    /**
     * @param array $functionMatch
     *
     * @return bool
     */
    protected function assertRequiredProperties(array $functionMatch)
    {
        if (!isset($functionMatch[1]) && !isset($functionMatch[2])) {
            return false;
        }

        return true;
    }

    /**
     * @param array $functionMatch
     *
     * @return string
     */
    protected function extractFunctionName(array $functionMatch)
    {
        return $functionMatch[1];
    }

    /**
     * @param array $functionMatch
     *
     * @return array
     */
    protected function extractFunctionParameters(array $functionMatch)
    {
        $parameters = [];
        foreach (explode(',', $functionMatch[2]) as $parameter) {
            $parameters[] = $this->sanitizeParameter($parameter);
        }
        return $parameters;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function sanitizeParameter($value)
    {
        return trim($value, static::TRIM_WHITELIST);
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected function matchFunctions($content)
    {
        $functionMatches = [];
        preg_match_all(
            static::TWIG_FUNCTION_WITH_PARAMETER_REGEXP,
            $content,
            $functionMatches,
            PREG_SET_ORDER,
            0
        );
        return $functionMatches;
    }
}
