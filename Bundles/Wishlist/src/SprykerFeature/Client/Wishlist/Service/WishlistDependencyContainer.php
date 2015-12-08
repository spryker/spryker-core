<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use SprykerFeature\Client\Wishlist\Service\Storage\WishlistStorage;
use SprykerFeature\Client\Wishlist\Service\Zed\WishlistStub;
use SprykerFeature\Client\Wishlist\Service\Session\WishlistSession;
use Generated\Client\Ide\FactoryAutoCompletion\WishlistService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Customer\Service\CustomerClientInterface;
use SprykerFeature\Client\Wishlist\Service\Session\WishlistSessionInterface;
use SprykerFeature\Client\Wishlist\Service\Storage\WishlistStorageInterface;
use SprykerFeature\Client\Wishlist\Service\Zed\WishlistStubInterface;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;

class WishlistDependencyContainer extends AbstractServiceDependencyContainer
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
