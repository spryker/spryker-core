<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSearchConfigStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSearchConfigStorage\Dependency\Client\ProductSearchConfigStorageToStorageClientInterface;
use Spryker\Client\ProductSearchConfigStorage\Dependency\Service\ProductSearchConfigStorageToSynchronizationServiceInterface;

class ProductSearchConfigStorageFactory extends AbstractFactory
{
    /**
     * @return ProductSearchConfigStorageToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return ProductSearchConfigStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::STORE);
    }
}
