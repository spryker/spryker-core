<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToProductLabelStorageClientBridge;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToSearchClientBridge;
use Spryker\Client\ProductNew\Plugin\Elasticsearch\Query\NewProductsQueryPlugin;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\ProductNew\ProductNewConfig getConfig()
 */
class ProductNewDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';
    public const STORE = 'STORE';
    public const NEW_PRODUCTS_QUERY_PLUGIN = 'NEW_PRODUCTS_QUERY_PLUGIN';
    public const NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS = 'NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS';
    public const NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS = 'NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductLabelStorageClient($container);
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
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return new ProductNewToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductLabelStorageClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_LABEL_STORAGE] = function (Container $container) {
            return new ProductNewToProductLabelStorageClientBridge($container->getLocator()->productLabelStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
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
        $container[static::NEW_PRODUCTS_QUERY_PLUGIN] = function () {
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
        $container[static::NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->getNewProductsQueryExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsResultFormatterPlugins(Container $container)
    {
        $container[static::NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->getNewProductsResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
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
