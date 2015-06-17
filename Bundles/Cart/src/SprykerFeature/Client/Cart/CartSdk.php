<?php

namespace SprykerFeature\Client\Cart;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Cart\StorageProvider\StorageProviderInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartClient extends AbstractClient
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
