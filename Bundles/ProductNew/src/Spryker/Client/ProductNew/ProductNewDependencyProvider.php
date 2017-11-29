<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductLabel\ProductLabelClient;
use Spryker\Client\ProductNew\Plugin\Elasticsearch\Query\NewProductsQueryPlugin;
use Spryker\Client\Search\SearchClient;
use Spryker\Shared\Kernel\Store;

class ProductNewDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_SEARCH = 'CLIENT_SEARCH';
    const CLIENT_PRODUCT_LABEL = 'CLIENT_PRODUCT_LABEL';
    const STORE = 'STORE';
    const NEW_PRODUCTS_QUERY_PLUGIN = 'NEW_PRODUCTS_QUERY_PLUGIN';
    const NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS = 'NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS';
    const NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS = 'NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductLabelClient($container);
        $container = $this->addStore($container);
        $container = $this->addNewProductsQueryPlugin($container);
        $container = $this->addNewProductsQueryExpanderPlugins($container);
        $container = $this->addNewProductsResultFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container[self::CLIENT_SEARCH] = function () {
            // TODO: use bridge + locator
            return new SearchClient();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductLabelClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_LABEL] = function () {
            // TODO: use bridge + locator
            return new ProductLabelClient();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container$container)
    {
        $container[self::STORE] = function () {
            // TODO: use bridge + locator + new store module
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsQueryPlugin(Container $container)
    {
        $container[self::NEW_PRODUCTS_QUERY_PLUGIN] = function () {
            return $this->getNewProductsQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsQueryExpanderPlugins(Container $container)
    {
        $container[self::NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->getNewProductsQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsResultFormatterPlugins(Container$container)
    {
        $container[self::NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->getNewProductsResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function getNewProductsQueryPlugin()
    {
        return new NewProductsQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getNewProductsQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getNewProductsResultFormatterPlugins()
    {
        return [];
    }
}
