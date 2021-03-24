<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductBridge;
use Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeBridge;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchBridge;

/**
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 */
class StockDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_STORE = 'FACADE_STORE';

    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    public const PLUGINS_STOCK_UPDATE = 'PLUGINS_STOCK_UPDATE';
    public const PLUGINS_STOCK_COLLECTION_EXPANDER = 'PLUGINS_STOCK_COLLECTION_EXPANDER';
    public const PLUGINS_STOCK_POST_CREATE = 'PLUGINS_STOCK_POST_CREATE';
    public const PLUGINS_STOCK_POST_UPDATE = 'PLUGINS_STOCK_POST_UPDATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addTouchFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addStockUpdatePlugins($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addStockCollectionExpanderPlugins($container);
        $container = $this->addStockPostCreatePlugins($container);
        $container = $this->addStockPostUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new StockToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOCK_UPDATE, function (Container $container) {
            return $this->getStockUpdateHandlerPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new StockToProductBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container): Container
    {
        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new StockToTouchBridge($container->getLocator()->touch()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOCK_COLLECTION_EXPANDER, function () {
            return $this->getStockCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOCK_POST_CREATE, function () {
            return $this->getStockPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOCK_POST_UPDATE, function () {
            return $this->getStockPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected function getStockUpdateHandlerPlugins(Container $container)
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockCollectionExpanderPluginInterface[]
     */
    protected function getStockCollectionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface[]
     */
    protected function getStockPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[]
     */
    protected function getStockPostUpdatePlugins(): array
    {
        return [];
    }
}
