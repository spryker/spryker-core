<?php

namespace SprykerFeature\Sdk\Cart;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartSdk extends AbstractSdk
{
    /**
     * @param StorageProviderInterface $storageProvider
     *
     * @return Model\CartInterface
     */
    public function createCart(StorageProviderInterface $storageProvider)
    {
        return $this->getDependencyContainer()->createCart($storageProvider);
    }
}
