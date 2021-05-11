<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Wishlist\Cart\CartHandler;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface;
use Spryker\Client\Wishlist\Product\ProductStorage;
use Spryker\Client\Wishlist\Zed\WishlistStub;

class WishlistFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Wishlist\Zed\WishlistStubInterface
     */
    public function createZedStub()
    {
        return new WishlistStub(
            $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return \Spryker\Client\Wishlist\Product\ProductStorageInterface
     */
    public function createProductStorage()
    {
        return new ProductStorage(
            $this->createProductClient(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface
     */
    public function createProductClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface
     */
    public function createCartClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_CART);
    }

    /**
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface $cartClient
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     *
     * @return \Spryker\Client\Wishlist\Cart\CartHandler
     */
    public function createCartHandler(WishlistToCartInterface $cartClient, WishlistClientInterface $wishlistClient)
    {
        return new CartHandler(
            $cartClient,
            $wishlistClient,
            $this->getWishlistPostMoveToCartCollectionExpanderPlugins(),
            $this->getWishlistCollectionToRemoveExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Wishlist\Dependency\Client\WishlistToCustomerInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Wishlist\Dependency\Client\WishlistToPriceProductClientInterface
     */
    protected function getPriceProductClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface[]
     */
    protected function getWishlistPostMoveToCartCollectionExpanderPlugins()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::PLUGINS_WISHLIST_POST_MOVE_TO_CART_COLLECTION_EXPANDER);
    }

    /**
     * @return \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface[]
     */
    protected function getWishlistCollectionToRemoveExpanderPlugins()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::PLUGINS_WISHLIST_COLLECTION_TO_REMOVE_EXPANDER);
    }
}
