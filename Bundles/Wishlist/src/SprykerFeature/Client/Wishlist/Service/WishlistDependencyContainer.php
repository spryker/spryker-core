<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Client\Ide\FactoryAutoCompletion\WishlistService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Customer\Service\CustomerClientInterface;
use SprykerFeature\Client\Wishlist\Service\Session\WishlistSessionInterface;
use SprykerFeature\Client\Wishlist\Service\Storage\WishlistStorageInterface;
use SprykerFeature\Client\Wishlist\Service\Zed\WishlistStubInterface;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;

/**
 * @method WishlistService getFactory()
 */
class WishlistDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return WishlistSessionInterface
     */
    public function createSession()
    {
        $session = $this->getFactory()->createSessionWishlistSession(
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
        $cartStub = $this->getFactory()->createZedWishlistStub($zedStub);

        return $cartStub;
    }

    /**
     * @return WishlistStorageInterface
     */
    public function createStorage()
    {
        return $this->getFactory()->createStorageWishlistStorage(
            $this->getProvidedDependency(WishlistDependencyProvider::STORAGE),
            $this->getProvidedDependency(WishlistDependencyProvider::PRODUCT_CLIENT)
        );
    }

    /**
     * @return CustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(WishlistDependencyProvider::CUSTOMER_CLIENT);
    }
}
