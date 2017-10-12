<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business\ContentWidget;

use Generated\Shared\Transfer\CmsContentWidgetFunctionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CmsContentWidget\Dependency\Facade\CmsContentWidgetToGlossaryInterface;

class ContentWidgetParameterMapper implements ContentWidgetParameterMapperInterface
{
    /**
     * @var array|\Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[]
     */
    protected $contentWidgetParameterMapperPlugins = [];

    /**
     * @var \Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetFunctionMatcherInterface
     */
    protected $contentWidgetFunctionMatcher;

    /**
     * @var array
     */
    protected static $mapCache = [];

    /**
     * @var \Spryker\Zed\CmsContentWidget\Dependency\Facade\CmsContentWidgetToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[] $contentWidgetParameterMapperPlugins
     * @param \Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetFunctionMatcherInterface $contentWidgetFunctionMatcher
     * @param \Spryker\Zed\CmsContentWidget\Dependency\Facade\CmsContentWidgetToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        array $contentWidgetParameterMapperPlugins,
        ContentWidgetFunctionMatcherInterface $contentWidgetFunctionMatcher,
        CmsContentWidgetToGlossaryInterface $glossaryFacade
    ) {
        $this->contentWidgetParameterMapperPlugins = $contentWidgetParameterMapperPlugins;
        $this->contentWidgetFunctionMatcher = $contentWidgetFunctionMatcher;
        $this->glossaryFacade = $glossaryFacade;
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

            $contentWidgetParameterMap[$functionName] = $contentWidgetParameterMap[$functionName] + $mappedParameters;
        }

        return $contentWidgetParameterMap;
    }

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapByTranslationKey($translationKey, LocaleTransfer $localeTransfer)
    {
        $content = $this->glossaryFacade->translate($translationKey, [], $localeTransfer);

        return $this->map($content);
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
            return;
        }

        $mappedParameters = $this->contentWidgetParameterMapperPlugins[$functionName]->map($unProcessedFunctionParameters);

        if (!isset(static::$mapCache[$functionName])) {
            static::$mapCache[$functionName] = [];
        }

        static::$mapCache[$functionName] = static::$mapCache[$functionName] + $mappedParameters;
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
