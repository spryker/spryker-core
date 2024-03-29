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
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

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
     * @phpstan-return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
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
}
