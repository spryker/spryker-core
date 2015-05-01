<?php

namespace SprykerFeature\Sdk\Cart2;

use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Cart2\Model\CartInterface;
use SprykerFeature\Sdk\Cart2\StorageProvider\StorageProviderInterface;

class Cart2DependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param StorageProviderInterface $storageProvider
     *
     * @return CartInterface
     */
    public function createCart(StorageProviderInterface $storageProvider)
    {
        return $this->getFactory()->createModelCart($storageProvider);
    }
}
