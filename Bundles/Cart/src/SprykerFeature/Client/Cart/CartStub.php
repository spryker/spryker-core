<?php

namespace SprykerFeature\Client\Cart;

use SprykerEngine\Client\Kernel\AbstractStub;
use SprykerFeature\Client\Cart\StorageProvider\StorageProviderInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartStub extends AbstractStub
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
