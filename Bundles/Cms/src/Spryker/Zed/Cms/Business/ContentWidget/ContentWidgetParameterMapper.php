<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\ContentWidget;

class ContentWidgetParameterMapper implements ContentWidgetParameterMapperInterface
{

    const TWIG_FUNCTION_WITH_PARAMETER_REGEXP = '/{{(?:\s)?(?:&nbsp;)?([a-z_-]+)\(\[?(.*?)\]?\)(?:\s)?(?:&nbsp;)?}}/i';
    const TRIM_WHITELIST = "'\" \t\n\r\v";

    /**
     * @var array|\Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[]
     */
    protected $contentWidgetParameterMapperPlugins = [];

    /**
     * @var array
     */
    protected static $mapCache = [];

    /**
     * @param array|\Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[] $contentWidgetParameterMapperPlugins
     */
    public function __construct(array $contentWidgetParameterMapperPlugins)
    {
        $this->contentWidgetParameterMapperPlugins = $contentWidgetParameterMapperPlugins;
    }

    /**
     * @param string $content
     *
     * @return array
     */
    public function map($content)
    {
        if (!$this->isTwigContent($content)) {
            return [];
        }

        $twigFunctionMatches = $this->extractTwigFunctions($content);

        if (count($twigFunctionMatches) === 0) {
            return [];
        }

        $contentWidgetParameterMap = [];
        foreach ($twigFunctionMatches as $functionMatch) {
            if (!$this->assertRequiredProperties($functionMatch)) {
                continue;
            }

            $functionName = $this->extractFunctionName($functionMatch);
            $functionParameters = $this->extractFunctionParameters($functionMatch);

            $unProcessedFunctionParameters = $this->collectFunctionParameters($functionParameters, $functionName);
            if (count($unProcessedFunctionParameters) > 0) {
                $this->buildParameterMap($functionName, $unProcessedFunctionParameters);
            }

            if (!isset($contentWidgetParameterMap[$functionName])) {
                $contentWidgetParameterMap[$functionName] = [];
            }

            $mappedParameters = $this->getMappedParameters($functionName, $functionParameters);

            $contentWidgetParameterMap[$functionName] = array_merge(
                $contentWidgetParameterMap[$functionName],
                $mappedParameters
            );
        }

        return $contentWidgetParameterMap;
    }

    /**
     * @param string $content
     *
     * @return bool
     */
    protected function isTwigContent($content)
    {
        if (strpos($content, '{{') !== false && strpos($content, '}}') !== false) {
            return true;
        }

        return false;
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
     * @param string $functionName
     * @param array $functionParameters
     *
     * @return void
     */
    protected function buildParameterMap($functionName, array $functionParameters)
    {
        if (!isset($this->contentWidgetParameterMapperPlugins[$functionName])) {
            return null;
        }

        $mappedParameters = $this->contentWidgetParameterMapperPlugins[$functionName]->map($functionParameters);

        static::$mapCache[$functionName] = array_merge($mappedParameters, $mappedParameters);
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected function extractTwigFunctions($content)
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

    /**
     * @param array $providedParameters
     * @param string $functionName
     *
     * @return array
     */
    protected function collectFunctionParameters(array $providedParameters, $functionName)
    {
        $functionParameters = [];
        foreach ($providedParameters as $parameter) {
            if (isset(static::$mapCache[$functionName]) && isset(static::$mapCache[$functionName][$parameter])) {
                continue;
            }

            $functionParameters[] = $parameter;
        }
        return $functionParameters;
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
     * @param string $functionName
     * @param array $functionParameters
     *
     * @return array
     */
    protected function getMappedParameters($functionName, array $functionParameters)
    {
        $mappedParameters = [];
        foreach ($functionParameters as $parameter) {
            if (!isset(static::$mapCache[$functionName]) || !isset(static::$mapCache[$functionName][$parameter])) {
                continue;
            }
            $mappedParameters[$parameter] = static::$mapCache[$functionName][$parameter];
        }

        return $mappedParameters;
    }

}
