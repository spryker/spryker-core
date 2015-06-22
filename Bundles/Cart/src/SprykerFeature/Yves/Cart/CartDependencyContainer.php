<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart;

use Generated\Yves\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Cart\Model\CartInterface;
use SprykerFeature\Client\Cart\StorageProvider\StorageProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @method Cart getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{
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
