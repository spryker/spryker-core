<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\Query\CmsPageSearchQueryPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class CmsPageSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const PLUGIN_CMS_PAGE_SEARCH_QUERY = 'PLUGIN_CMS_PAGE_SEARCH_QUERY';
    public const PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER = 'PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER = 'PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addSearchClient($container);
        $container = $this->addCmsPageSearchQueryPlugin($container);
        $container = $this->addCmsPageSearchResultFormatterPlugins($container);
        $container = $this->addCmsPageSearchQueryExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryPlugin(Container $container): Container
    {
        $container[static::PLUGIN_CMS_PAGE_SEARCH_QUERY] = function () {
            return $this->createCmsPageSearchQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchResultFormatterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER] = function () {
            return $this->createCmsPageSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER] = function () {
            return $this->createCmsPageSearchQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createCmsPageSearchQueryPlugin(): QueryInterface
    {
        return new CmsPageSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCmsPageSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCmsPageSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
