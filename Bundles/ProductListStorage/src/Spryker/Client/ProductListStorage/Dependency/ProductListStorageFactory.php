<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\Dependency;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductListStorage\ProductListStorageDependencyProvider;

class ProductListStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductListStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductListStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
