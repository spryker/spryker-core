<?php

namespace SprykerFeature\Client\Cart;

use Generated\Client\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Cart\Zed\CartStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method Cart getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SessionInterface
     */
    public function createSession()
    {
        return $this->getProvidedDependency(CartDependencyProvider::SESSION);
    }

    /**
     * @return CartStubInterface
     */
    public function createStub()
    {
        $zedStub = $this->getProvidedDependency(CartDependencyProvider::SERVICE_ZED);
        $cartStub = $this->getFactory()->createServiceZedCartStub(
            $zedStub
        );

        return $cartStub;
    }






















    /**
     * @param SessionInterface $session
     *
     * @return StorageProviderInterface
     */
    protected function createStorageProvider(SessionInterface $session)
    {
        $client = $this->getLocator()->cart()->client();

        return $this->getFactory()->createProviderSessionStorageProvider($this->getLocator(), $session);


    }

    /**
     * @param SessionInterface $session
     *
     * @return CartInterface
     */
    public function createCartClient(SessionInterface $session)
    {
        return $this->getLocator()
            ->cart()
            ->client()
            ->getCart($this->createStorageProvider($session))
        ;
    }

}
