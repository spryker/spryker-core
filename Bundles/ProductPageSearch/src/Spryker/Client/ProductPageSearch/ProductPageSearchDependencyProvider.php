<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductPageSearch\Dependency\Client\ProductPageSearchToSearchClientBridge;
use Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;

/**
 * @method \Spryker\Client\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductPageSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const PLUGIN_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY = 'PLUGIN_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY';
    public const PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER = 'PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY_EXPANDER';
    public const PLUGIN_PAGINATION_CONFIG_BUILDER = 'PLUGIN_PAGINATION_CONFIG_BUILDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductConcretePageSearchResultFormatterPlugins($container);
        $container = $this->addProductConcretePageSearchQueryPlugin($container);
        $container = $this->addProductConcretePageSearchQueryExpanderPlugins($container);
        $container = $this->addPaginationConfigBuilderPlugin($container);

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
            return new ProductPageSearchToSearchClientBridge(
                $container->getLocator()->search()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcretePageSearchResultFormatterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER] = function (Container $container) {
            return $this->getProductConcretePageSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcretePageSearchQueryExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY_EXPANDER] = function (Container $container) {
            return $this->getProductConcretePageSearchQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcretePageSearchQueryPlugin(Container $container): Container
    {
        $container[static::PLUGIN_PRODUCT_CONCRETE_PAGE_SEARCH_QUERY] = function (Container $container) {
            return $this->createProductConcretePageSearchQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPaginationConfigBuilderPlugin(Container $container)
    {
        $container[static::PLUGIN_PAGINATION_CONFIG_BUILDER] = function (Container $container) {
            return new PaginationConfigBuilder();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createProductConcretePageSearchQueryPlugin(): QueryInterface
    {
        return new ProductConcretePageSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getProductConcretePageSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getProductConcretePageSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
