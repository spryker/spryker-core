<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\ContentWidget;

use Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer;
use Spryker\Zed\Cms\Business\ContentWidget\ContentWidgetFunctionMatcherInterface;

class ContentWidgetParameterMapper implements ContentWidgetParameterMapperInterface
{

    /**
     * @var array|\Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[]
     */
    protected $contentWidgetParameterMapperPlugins = [];

    /**
     * @var \Spryker\Zed\Cms\Business\ContentWidget\ContentWidgetFunctionMatcherInterface
     */
    protected $contentWidgetFunctionMatcher;

    /**
     * @var array
     */
    protected static $mapCache = [];

    /**
     * @param array|\Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[] $contentWidgetParameterMapperPlugins
     * @param \Spryker\Zed\Cms\Business\ContentWidget\ContentWidgetFunctionMatcherInterface $contentWidgetFunctionMatcher
     */
    public function __construct(
        array $contentWidgetParameterMapperPlugins,
        ContentWidgetFunctionMatcherInterface $contentWidgetFunctionMatcher
    ) {
        $this->contentWidgetParameterMapperPlugins = $contentWidgetParameterMapperPlugins;
        $this->contentWidgetFunctionMatcher = $contentWidgetFunctionMatcher;
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

        $cmsContentWidgetFunctions = $this->contentWidgetFunctionMatcher->extractTwigFunctions($content);
        if (count($cmsContentWidgetFunctions->getCmsContentWidgetFunctionList()) === 0) {
            return [];
        }

        $contentWidgetParameterMap = [];
        foreach ($cmsContentWidgetFunctions->getCmsContentWidgetFunctionList() as $cmsContentWidgetFunctionTransfer) {

            $this->updateMapCacheWithUnprocessedItems($cmsContentWidgetFunctionTransfer);
            $mappedParameters = $this->getMappedParameters($cmsContentWidgetFunctionTransfer);

            $functionName = $cmsContentWidgetFunctionTransfer->getFunctionName();
            if (!isset($contentWidgetParameterMap[$functionName])) {
                $contentWidgetParameterMap[$functionName] = [];
            }

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
     * @param string $functionName
     * @param array $unProcessedFunctionParameters
     *
     * @return void
     */
    protected function buildParameterMap($functionName, array $unProcessedFunctionParameters)
    {
        if (!isset($this->contentWidgetParameterMapperPlugins[$functionName])) {
            return null;
        }

        $mappedParameters = $this->contentWidgetParameterMapperPlugins[$functionName]->map($unProcessedFunctionParameters);

        static::$mapCache[$functionName] = array_merge($mappedParameters, $mappedParameters);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer
     *
     * @return array
     */
    protected function collectFunctionParameters(CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer)
    {
        $functionParameters = [];
        $functionName = $cmsContentWidgetFunctionTransfer->getFunctionName();
        foreach ($cmsContentWidgetFunctionTransfer->getParameters() as $parameter) {
            if (isset(static::$mapCache[$functionName]) && isset(static::$mapCache[$functionName][$parameter])) {
                continue;
            }

            $functionParameters[] = $parameter;
        }
        return $functionParameters;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer
     *
     * @return array
     */
    protected function getMappedParameters(CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer)
    {
        $mappedParameters = [];
        $functionName = $cmsContentWidgetFunctionTransfer->getFunctionName();
        foreach ($cmsContentWidgetFunctionTransfer->getParameters() as $parameter) {
            if (!isset(static::$mapCache[$functionName]) || !isset(static::$mapCache[$functionName][$parameter])) {
                continue;
            }
            $mappedParameters[$parameter] = static::$mapCache[$functionName][$parameter];
        }

        return $mappedParameters;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer
     *
     * @return void
     */
    protected function updateMapCacheWithUnprocessedItems(CmsContentWidgetFunctionTransfer $cmsContentWidgetFunctionTransfer)
    {
        $unProcessedFunctionParameters = $this->collectFunctionParameters($cmsContentWidgetFunctionTransfer);
        if (count($unProcessedFunctionParameters) > 0) {
            $this->buildParameterMap($cmsContentWidgetFunctionTransfer->getFunctionName(), $unProcessedFunctionParameters);
        }
    }

}
