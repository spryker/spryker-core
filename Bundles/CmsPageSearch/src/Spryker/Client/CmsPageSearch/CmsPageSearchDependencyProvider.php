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
    public const CLIENT_SEARCH = 'search_client';
    public const CMS_PAGE_SEARCH_QUERY_PLUGIN = 'cms_page_search_query_plugin';
    public const CMS_PAGE_SEARCH_RESULT_FORMATTER_PLUGINS = 'cms_page_search_result_formatter_plugins';
    public const CMS_PAGE_SEARCH_QUERY_EXPANDER_PLUGINS = 'cms_page_search_query_expander_plugins';

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
        $container[static::CMS_PAGE_SEARCH_QUERY_PLUGIN] = function () {
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
        $container[static::CMS_PAGE_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
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
        $container[static::CMS_PAGE_SEARCH_QUERY_EXPANDER_PLUGINS] = function () {
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
