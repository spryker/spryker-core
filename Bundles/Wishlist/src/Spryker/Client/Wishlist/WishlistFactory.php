<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Wishlist\Storage\WishlistStorage;
use Spryker\Client\Wishlist\Zed\WishlistStub;
use Spryker\Client\Wishlist\Session\WishlistSession;
use Spryker\Client\Kernel\AbstractFactory;

class WishlistFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Wishlist\Session\WishlistSessionInterface
     */
    public function createSession()
    {
        $session = new WishlistSession(
            $this->getProvidedDependency(WishlistDependencyProvider::SESSION)
        );

        return $session;
    }

    /**
     * @return \Spryker\Client\Wishlist\Zed\WishlistStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED);
        $cartStub = new WishlistStub($zedStub);

        return $cartStub;
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
