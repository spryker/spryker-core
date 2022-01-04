<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToLocaleClientBridge;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToProductLabelStorageClientBridge;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToSearchClientBridge;
use Spryker\Client\ProductNew\Dependency\Client\ProductNewToStoreClientBridge;
use Spryker\Client\ProductNew\Plugin\Elasticsearch\Query\NewProductsQueryPlugin;

/**
 * @method \Spryker\Client\ProductNew\ProductNewConfig getConfig()
 */
class ProductNewDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';

    /**
     * @var string
     */
    public const NEW_PRODUCTS_QUERY_PLUGIN = 'NEW_PRODUCTS_QUERY_PLUGIN';

    /**
     * @var string
     */
    public const NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS = 'NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS';

    /**
     * @var string
     */
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
        $container = $this->addLocaleClient($container);
        $container = $this->addStoreClient($container);
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
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new ProductNewToSearchClientBridge($container->getLocator()->search()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductLabelStorageClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_LABEL_STORAGE, function (Container $container) {
            return new ProductNewToProductLabelStorageClientBridge($container->getLocator()->productLabelStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsQueryPlugin(Container $container)
    {
        $container->set(static::NEW_PRODUCTS_QUERY_PLUGIN, function () {
            return $this->getNewProductsQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsQueryExpanderPlugins(Container $container)
    {
        $container->set(static::NEW_PRODUCTS_QUERY_EXPANDER_PLUGINS, function () {
            return $this->getNewProductsQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addNewProductsResultFormatterPlugins(Container $container)
    {
        $container->set(static::NEW_PRODUCTS_RESULT_FORMATTER_PLUGINS, function () {
            return $this->getNewProductsResultFormatterPlugins();
        });

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
     * @return array<\Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getNewProductsQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getNewProductsResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new ProductNewToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductNewToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }
}
