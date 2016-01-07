<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Wishlist\Storage\WishlistStorage;
use Spryker\Client\Wishlist\Zed\WishlistStub;
use Spryker\Client\Wishlist\Session\WishlistSession;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\Wishlist\Session\WishlistSessionInterface;
use Spryker\Client\Wishlist\Storage\WishlistStorageInterface;
use Spryker\Client\Wishlist\Zed\WishlistStubInterface;

class WishlistFactory extends AbstractFactory
{

    /**
     * @return WishlistSessionInterface
     */
    public function createSession()
    {
        $session = new WishlistSession(
            $this->getProvidedDependency(WishlistDependencyProvider::SESSION)
        );

        return $session;
    }

    /**
     * @return WishlistStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED);
        $cartStub = new WishlistStub($zedStub);

        return $cartStub;
    }

    /**
     * @return WishlistStorageInterface
     */
    public function createStorage()
    {
        return new WishlistStorage(
            $this->getProvidedDependency(WishlistDependencyProvider::STORAGE),
            $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_PRODUCT)
        );
    }

    /**
     * @return CustomerClientInterface
     */
    public function createCustomerClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CLIENT_CUSTOMER);
    }

}
