<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Wishlist\Cart\CartHandler;
use Spryker\Client\Wishlist\Storage\WishlistStorage;
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
     * @return \Spryker\Client\Wishlist\Storage\WishlistStorageInterface
     */
    public function createStorage()
    {
        return new WishlistStorage(
            $this->getProvidedDependency(WishlistDependencyProvider::STORAGE),
            $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_PRODUCT)
        );
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function createCustomerClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function createProductClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function createCartClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_CART);
    }

    /**
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     *
     * @return \Spryker\Client\Wishlist\Cart\CartHandler
     */
    public function createCartHandler(CartClientInterface $cartClient, WishlistClientInterface $wishlistClient)
    {
        return new CartHandler(
            $cartClient,
            $wishlistClient
        );
    }

}
