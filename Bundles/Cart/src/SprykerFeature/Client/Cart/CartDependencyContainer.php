<?php

namespace SprykerFeature\Client\Cart;

use Generated\Client\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Cart\Model\CartInterface;
use SprykerFeature\Client\Cart\StorageProvider\StorageProviderInterface;

/**
 * @method Cart getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param StorageProviderInterface $storageProvider
     *
     * @return CartInterface
     */
    public function createCart(StorageProviderInterface $storageProvider)
    {
        $zedClient = $this->getLocator()->zedRequest()->zedClient()->getInstance();
        return $this->getFactory()->createModelCart(
            $zedClient,
            $storageProvider
        );
    }
}
