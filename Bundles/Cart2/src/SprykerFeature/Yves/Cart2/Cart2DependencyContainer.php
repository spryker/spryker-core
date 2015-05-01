<?php

namespace SprykerFeature\Yves\Cart2;

use Generated\Sdk\Ide\AutoCompletion;
use Generated\Yves\Ide\FactoryAutoCompletion\Cart2;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerEngine\Yves\Kernel\Factory;
use SprykerFeature\Sdk\Cart2\Model\CartInterface;
use SprykerFeature\Sdk\Cart2\StorageProvider\StorageProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart2DependencyContainer extends AbstractDependencyContainer
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
            ->cart2()
            ->sdk()
            ->createCart($this->createStorageProvider($session))
        ;
    }
}
