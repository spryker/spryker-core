<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchDependencyProvider extends AbstractDependencyProvider
{
    public const STORE = 'STORE';
    public const SEARCH_CONFIG_EXPANDER_PLUGINS = 'SEARCH_CONFIG_EXPANDER_PLUGINS';
    public const SEARCH_FACET_CONFIG_BUILDER_PLUGIN = 'SEARCH_FACET_CONFIG_BUILDER_PLUGIN';
    public const SEARCH_PAGINATION_CONFIG_BUILDER_PLUGIN = 'SEARCH_PAGINATION_CONFIG_BUILDER_PLUGIN';
    public const SEARCH_SORT_CONFIG_BUILDER_PLUGIN = 'SEARCH_SORT_CONFIG_BUILDER_PLUGIN';

    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addStore($container);
        $container = $this->addSearchConfigExpanderPlugins($container);
        $container = $this->addFacetSearchConfigBuilder($container);
        $container = $this->addPaginationSearchConfigBuilder($container);
        $container = $this->addSortSearchConfigBuilder($container);
        $container = $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addFacetSearchConfigBuilder(Container $container): Container
    {
        $container->set(static::SEARCH_FACET_CONFIG_BUILDER_PLUGIN, function (Container $container) {
            return $this->getFacetSearchConfigBuilderPlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface|null
     */
    protected function getFacetSearchConfigBuilderPlugin(Container $container): ?FacetConfigPluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPaginationSearchConfigBuilder(Container $container): Container
    {
        $container->set(static::SEARCH_PAGINATION_CONFIG_BUILDER_PLUGIN, function (Container $container) {
            return $this->getPaginationSearchConfigBuilderPlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface|null
     */
    protected function getPaginationSearchConfigBuilderPlugin(Container $container): ?PaginationConfigPluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSortSearchConfigBuilder(Container $container): Container
    {
        $container->set(static::SEARCH_SORT_CONFIG_BUILDER_PLUGIN, function (Container $container) {
            return $this->getSortSearchConfigBuilderPlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface|null
     */
    protected function getSortSearchConfigBuilderPlugin(Container $container): ?SortConfigPluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::SEARCH_CONFIG_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getSearchConfigExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected function getSearchConfigExpanderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, function () {
            return $this->getMoneyPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function getMoneyPlugin(): MoneyPluginInterface
    {
        return new MoneyPlugin();
    }
}
