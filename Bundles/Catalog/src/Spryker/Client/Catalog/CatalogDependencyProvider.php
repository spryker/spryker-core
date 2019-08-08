<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\CatalogSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\ProductConcreteCatalogSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\SuggestionQueryPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;

/**
 * @method \Spryker\Client\Catalog\CatalogConfig getConfig()
 */
class CatalogDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'search client';
    public const CATALOG_SEARCH_QUERY_PLUGIN = 'catalog search query plugin';
    public const CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS = 'catalog search query expander plugins';
    public const CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS = 'catalog search result formatter plugins';
    public const SUGGESTION_QUERY_PLUGIN = 'suggestion query plugin';
    public const SUGGESTION_QUERY_EXPANDER_PLUGINS = 'suggestion query expander plugins';
    public const SUGGESTION_RESULT_FORMATTER_PLUGINS = 'suggestion result formatter plugins';
    public const PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS = 'PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS';
    public const PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS = 'PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS';
    public const PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER = 'PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER';
    public const PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY = 'PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY';
    public const PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER = 'PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER';
    public const PLUGIN_PAGINATION_CONFIG_BUILDER = 'PLUGIN_PAGINATION_CONFIG_BUILDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addSearchClient($container);
        $container = $this->addCatalogSearchQueryPlugin($container);
        $container = $this->addCatalogSearchQueryExpanderPlugins($container);
        $container = $this->addCatalogSerachResultFormatterPlugins($container);
        $container = $this->addSuggestionQueryPlugin($container);
        $container = $this->addSuggestionQueryExpanderPlugins($container);
        $container = $this->addSuggestionResultFormatterPlugins($container);
        $container = $this->addFacetConfigTransferBuilderPlugins($container);
        $container = $this->addSortConfigTransferBuilderPlugins($container);
        $container = $this->addCatalogSearchCountQueryExpanderPlugins($container);
        $container = $this->addProductConcreteCatalogSearchResultFormatterPlugins($container);
        $container = $this->addProductConcreteCatalogSearchQueryPlugin($container);
        $container = $this->addProductConcreteCatalogSearchQueryExpanderPlugins($container);
        $container = $this->addPaginationConfigBuilderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container)
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
    protected function addCatalogSearchQueryPlugin(Container $container)
    {
        $container[static::CATALOG_SEARCH_QUERY_PLUGIN] = function () {
            return $this->createCatalogSearchQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSearchQueryExpanderPlugins(Container $container)
    {
        $container[static::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->createCatalogSearchQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSerachResultFormatterPlugins(Container $container)
    {
        $container[static::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->createCatalogSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSuggestionQueryPlugin(Container $container)
    {
        $container[static::SUGGESTION_QUERY_PLUGIN] = function () {
            return $this->createSuggestionQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSuggestionQueryExpanderPlugins(Container $container)
    {
        $container[static::SUGGESTION_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->createSuggestionQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSuggestionResultFormatterPlugins(Container $container)
    {
        $container[static::SUGGESTION_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->createSuggestionResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addFacetConfigTransferBuilderPlugins(Container $container)
    {
        $container[static::PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS] = function () {
            return $this->getFacetConfigTransferBuilderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSortConfigTransferBuilderPlugins(Container $container)
    {
        $container[static::PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS] = function () {
            return $this->getSortConfigTransferBuilderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSearchCountQueryExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER] = function () {
            return $this->createCatalogSearchCountQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteCatalogSearchResultFormatterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER] = function () {
            return $this->getProductConcreteCatalogSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteCatalogSearchQueryExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER] = function () {
            return $this->getProductConcreteCatalogSearchQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteCatalogSearchQueryPlugin(Container $container): Container
    {
        $container[static::PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY] = function () {
            return $this->createProductConcreteCatalogSearchQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPaginationConfigBuilderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_PAGINATION_CONFIG_BUILDER] = function () {
            return new PaginationConfigBuilder();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createCatalogSearchQueryPlugin()
    {
        return new CatalogSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCatalogSearchResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createSuggestionQueryPlugin()
    {
        return new SuggestionQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createSuggestionQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createSuggestionResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface[]
     */
    protected function getFacetConfigTransferBuilderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface[]
     */
    protected function getSortConfigTransferBuilderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchCountQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createProductConcreteCatalogSearchQueryPlugin(): QueryInterface
    {
        return new ProductConcreteCatalogSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getProductConcreteCatalogSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getProductConcreteCatalogSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
