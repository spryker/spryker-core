<?php

namespace SprykerFeature\Sdk\Cart;

use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Cart\Model\CartInterface;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;

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
