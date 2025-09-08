<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridge;
use Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\Query\CmsPageSearchQueryPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @deprecated use {@link static::PLUGINS_SEARCH_QUERY} instead.
     *
     * @var string
     */
    public const PLUGIN_CMS_PAGE_SEARCH_QUERY = 'PLUGIN_CMS_PAGE_SEARCH_QUERY';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER = 'PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER = 'PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_SEARCH_COUNT_QUERY_EXPANDER = 'PLUGINS_CMS_PAGE_SEARCH_COUNT_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_QUERY = 'PLUGINS_SEARCH_QUERY';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_HTTP_RESULT_FORMATTER = 'PLUGINS_SEARCH_HTTP_RESULT_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_HTTP_QUERY_EXPANDER = 'PLUGINS_SEARCH_HTTP_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_HTTP_COUNT_QUERY_EXPANDER = 'PLUGINS_SEARCH_HTTP_COUNT_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_RESULT_COUNT = 'PLUGINS_SEARCH_RESULT_COUNT';

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
        $container = $this->addCmsPageSearchQueryCountExpanderPlugins($container);
        $container = $this->addCmsPageSearchQueryPlugins($container);
        $container = $this->addCmsPageSearchHttpResultFormatterPlugins($container);
        $container = $this->addCmsPageSearchHttpQueryExpanderPlugins($container);
        $container = $this->addCmsPageSearchHttpQueryCountExpanderPlugins($container);
        $container = $this->addSearchResultCountPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new CmsPageSearchToSearchBridge($container->getLocator()->search()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CMS_PAGE_SEARCH_QUERY, function () {
            return $this->createCmsPageSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER, function () {
            return $this->createCmsPageSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER, function () {
            return $this->createCmsPageSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryCountExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_PAGE_SEARCH_COUNT_QUERY_EXPANDER, function () {
            return $this->createCmsPageSearchCountQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchQueryPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_QUERY, function () {
            return $this->getCmsPageSearchQueryPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchHttpResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_HTTP_RESULT_FORMATTER, function () {
            return $this->getCmsPageHttpSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchHttpQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_HTTP_QUERY_EXPANDER, function () {
            return $this->getCmsPageHttpSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsPageSearchHttpQueryCountExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_HTTP_COUNT_QUERY_EXPANDER, function () {
            return $this->getCmsPageHttpSearchCountQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchResultCountPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_RESULT_COUNT, function () {
            return $this->getCmsPageSearchResultCountPlugins();
        });

        return $container;
    }

    /**
     * @deprecated use {@link static::getCmsPageSearchQueryPlugins()} instead.
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createCmsPageSearchQueryPlugin(): QueryInterface
    {
        return new CmsPageSearchQueryPlugin();
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function createCmsPageSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function createCmsPageSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function createCmsPageSearchCountQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getCmsPageHttpSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getCmsPageHttpSearchCountQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getCmsPageHttpSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface>
     */
    protected function getCmsPageSearchQueryPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface>
     */
    protected function getCmsPageSearchResultCountPlugins(): array
    {
        return [];
    }
}
