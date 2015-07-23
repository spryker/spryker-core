<?php

namespace SprykerFeature\Client\Wishlist\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;


class WishlistDependencyContainer extends AbstractServiceDependencyContainer
{
    public function createWishlist()
    {
        return null;
    }

    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(WishlistDependencyProvider::SERVICE_ZED);
        return $this->getFactory()->createZedWishlistStub($zedStub);
    }

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        $session = $this->getFactory()->createSessionWishlistSession(
            $this->getProvidedDependency(WishlistDependencyProvider::SESSION)
        );

        return $session;
    }

}
