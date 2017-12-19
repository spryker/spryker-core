<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage;

use Spryker\Client\ProductOptionStorage\Storage\ProductOptionStorageReader;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class ProductOptionStorageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductOptionStorage\Storage\ProductOptionStorageReaderInterface
     */
    public function createProductOptionStorageReader()
    {
        return new ProductOptionStorageReader($this->getStorage(), $this->getSynchronizationService(), $this->getStore());
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::STORE);
    }

}
