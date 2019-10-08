<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business;

use Spryker\Zed\CmsContentWidget\Business\CmsBlockCollector\CmsBlockCollectorParameterMapExpander;
use Spryker\Zed\CmsContentWidget\Business\CmsPageCollector\CmsPageCollectorParameterMapExpander;
use Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetConfigurationListProvider;
use Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetFunctionMatcher;
use Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetParameterMapper;
use Spryker\Zed\CmsContentWidget\CmsContentWidgetDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig getConfig()
 */
class CmsContentWidgetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetParameterMapperInterface
     */
    public function createCmsContentWidgetParameterMapper()
    {
        return new ContentWidgetParameterMapper(
            $this->getCmsContentWidgetParameterMapperPlugins(),
            $this->createCmsContentWidgetFunctionMatcher(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsBlockCollector\CmsBlockCollectorParameterMapExpander
     */
    public function createCmsBlockCollectorParameterMapExpander()
    {
        return new CmsBlockCollectorParameterMapExpander($this->createCmsContentWidgetParameterMapper());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsPageCollector\CmsPageCollectorParameterMapExpander
     */
    public function createCmsPageCollectorParameterMapExpander()
    {
        return new CmsPageCollectorParameterMapExpander($this->createCmsContentWidgetParameterMapper());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetConfigurationListProviderInterface
     */
    public function createCmsContentWidgetTemplateListProvider()
    {
        return new ContentWidgetConfigurationListProvider($this->getConfig()->getCmsContentWidgetConfigurationProviders());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\ContentWidget\ContentWidgetFunctionMatcherInterface
     */
    protected function createCmsContentWidgetFunctionMatcher()
    {
        return new ContentWidgetFunctionMatcher();
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface[]
     */
    protected function getCmsContentWidgetParameterMapperPlugins()
    {
        return $this->getProvidedDependency(CmsContentWidgetDependencyProvider::PLUGINS_CMS_CONTENT_WIDGET_PARAMETER_MAPPERS);
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Dependency\Facade\CmsContentWidgetToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(CmsContentWidgetDependencyProvider::FACADE_GLOSSARY);
    }
}
