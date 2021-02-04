<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductBridge as FacadeWishlistToProductBridge;
use Spryker\Zed\Wishlist\Dependency\QueryContainer\WishlistToProductBridge as QueryContainerWishlistToProductBridge;

/**
 * @method \Spryker\Zed\Wishlist\WishlistConfig getConfig()
 */
class WishlistDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const PLUGINS_ITEM_EXPANDER = 'PLUGINS_ITEM_EXPANDER';
    public const PLUGINS_ADD_ITEM_PRE_CHECK = 'PLUGINS_ADD_ITEM_PRE_CHECK';
    public const PLUGINS_WISHLIST_RELOAD_ITEMS = 'PLUGINS_RELOAD_ITEMS';
    public const PLUGINS_WISHLIST_ITEMS_VALIDATOR = 'PLUGINS_WISHLIST_ITEMS_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addProductQueryContainer($container);
        $container = $this->addItemExpanderPlugins($container);
        $container = $this->addAddItemPreCheckPlugins($container);
        $container = $this->addWishlistReloadItemPlugins($container);
        $container = $this->addWishlistItemsValidatorPlugins($container);

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
            return new FacadeWishlistToProductBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new QueryContainerWishlistToProductBridge($container->getLocator()->product()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ITEM_EXPANDER, function () {
            return $this->getItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAddItemPreCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ADD_ITEM_PRE_CHECK, function () {
            return $this->getAddItemPreCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWishlistReloadItemPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_WISHLIST_RELOAD_ITEMS, function () {
            return $this->getWishlistReloadItemsPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWishlistItemsValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_WISHLIST_ITEMS_VALIDATOR, function () {
            return $this->getWishlistItemsValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\Wishlist\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected function getItemExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\WishlistExtension\Dependency\Plugin\AddItemPreCheckPluginInterface[]
     */
    protected function getAddItemPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistReloadItemsPluginInterface[]
     */
    protected function getWishlistReloadItemsPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemsValidatorPluginInterface[]
     */
    protected function getWishlistItemsValidatorPlugins(): array
    {
        return [];
    }
}
