<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CartService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Cart\CartDependencyProvider;
use SprykerFeature\Client\Cart\Service\Zed\CartStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SprykerFeature\Client\Cart\Service\Storage\CartStorageInterface;

/**
 * @method CartService getFactory()
 */
class CartDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        $session = $this->getFactory()->createSessionCartSession(
            $this->getProvidedDependency(CartDependencyProvider::SESSION)
        );

        return $session;
    }

    /**
     * @return CartStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(CartDependencyProvider::SERVICE_ZED);
        $cartStub = $this->getFactory()->createZedCartStub(
            $zedStub
        );

        return $cartStub;
    }

    /**
     * @return CartStorageInterface
     */
    public function createStorage()
    {
        $storage = $this->getProvidedDependency(CartDependencyProvider::KV_STORAGE);

        return $this->getFactory()->createStorageCartStorage($storage);
    }

}
