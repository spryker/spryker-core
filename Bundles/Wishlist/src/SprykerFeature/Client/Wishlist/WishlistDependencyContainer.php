<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist;

use SprykerFeature\Client\Wishlist\Storage\WishlistStorage;
use SprykerFeature\Client\Wishlist\Zed\WishlistStub;
use SprykerFeature\Client\Wishlist\Session\WishlistSession;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Customer\CustomerClientInterface;
use SprykerFeature\Client\Wishlist\Session\WishlistSessionInterface;
use SprykerFeature\Client\Wishlist\Storage\WishlistStorageInterface;
use SprykerFeature\Client\Wishlist\Zed\WishlistStubInterface;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;

class WishlistDependencyContainer extends AbstractDependencyContainer
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
