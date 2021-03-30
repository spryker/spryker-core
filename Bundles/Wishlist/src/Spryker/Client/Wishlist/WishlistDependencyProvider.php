<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCartBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCustomerBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToPriceProductClientClientBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToProductBridge;

class WishlistDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_ZED = 'SERVICE_ZED';

    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_PRODUCT = 'CLIENT_PRODUCT';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_PRICE_PRODUCT = 'CLIENT_PRICE_PRODUCT';

    public const PLUGINS_WISHLIST_POST_MOVE_TO_CART_COLLECTION_EXPANDER = 'PLUGINS_WISHLIST_POST_MOVE_TO_CART_COLLECTION_EXPANDER';
    public const PLUGINS_WISHLIST_COLLECTION_TO_REMOVE_EXPANDER = 'PLUGINS_WISHLIST_COLLECTION_TO_REMOVE_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addProductClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addPriceProductClient($container);
        $container = $this->addWishlistPostMoveToCartCollectionExpanderPlugins($container);
        $container = $this->addWishlistCollectionToRemoveExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container)
    {
        $container->set(static::CLIENT_PRICE_PRODUCT, function (Container $container) {
            return new WishlistToPriceProductClientClientBridge($container->getLocator()->priceProduct()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new WishlistToCustomerBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartClient(Container $container)
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return new WishlistToCartBridge($container->getLocator()->cart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT, function (Container $container) {
            return new WishlistToProductBridge($container->getLocator()->product()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container)
    {
        $container->set(static::SERVICE_ZED, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addWishlistPostMoveToCartCollectionExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_WISHLIST_POST_MOVE_TO_CART_COLLECTION_EXPANDER, function (Container $container) {
            return $this->getWishlistPostMoveToCartCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface[]
     */
    protected function getWishlistPostMoveToCartCollectionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addWishlistCollectionToRemoveExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_WISHLIST_COLLECTION_TO_REMOVE_EXPANDER, function (Container $container) {
            return $this->getWishlistCollectionToRemoveExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface[]
     */
    protected function getWishlistCollectionToRemoveExpanderPlugins(): array
    {
        return [];
    }
}
