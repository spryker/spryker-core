<?php

namespace SprykerFeature\Sdk\Cart;

use Generated\Sdk\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Cart\Model\CartInterface;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;

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
