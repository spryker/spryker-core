<?php

namespace SprykerFeature\Sdk\Cart2;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use SprykerFeature\Sdk\Cart2\StorageProvider\StorageProviderInterface;

/**
 * @method Cart2DependencyContainer getDependencyContainer()
 */
class Cart2Sdk extends AbstractSdk
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
