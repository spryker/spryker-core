<?php

namespace SprykerFeature\Client\Cart;

use Generated\Client\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

/**
 * @method Cart getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @throws \ErrorException
     * @return mixed
     */
    public function createSession()
    {
        return $this->getProvidedDependency(CartDependencyProvider::SESSION);
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
