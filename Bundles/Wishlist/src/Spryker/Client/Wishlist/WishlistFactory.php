<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Wishlist\Session\WishlistSession;
use Spryker\Client\Wishlist\Storage\WishlistStorage;
use Spryker\Client\Wishlist\Zed\WishlistStub;

class WishlistFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Wishlist\Session\WishlistSessionInterface
     */
    public function createSession()
    {
        return new WishlistSession(
            $this->getProvidedDependency(WishlistDependencyProvider::SESSION)
        );
    }

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

}
