<?php

namespace SprykerFeature\Yves\Cart;

use Generated\Sdk\Ide\AutoCompletion;
use Generated\Yves\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerEngine\Yves\Kernel\Factory;
use SprykerFeature\Sdk\Cart\Model\CartInterface;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param SessionInterface $session
     *
     * @return StorageProviderInterface
     */
    protected function createStorageProvider(SessionInterface $session)
    {
        return $this->getFactory()->createProviderSessionStorageProvider($this->getLocator(), $session);
    }

    /**
     * @param SessionInterface $session
     *
     * @return CartInterface
     */
    public function createCartSdk(SessionInterface $session)
    {
        return $this->getLocator()
            ->Cart()
            ->sdk()
            ->createCart($this->createStorageProvider($session))
        ;
    }
}
