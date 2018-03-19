<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReader;

class ProductQuantityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    public function createProductQuantityStorageReader()
    {
        return new ProductQuantityStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
