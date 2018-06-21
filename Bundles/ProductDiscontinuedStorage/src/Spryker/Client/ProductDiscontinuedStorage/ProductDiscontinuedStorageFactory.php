<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReader;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class ProductDiscontinuedStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    public function createProductDiscontinuedStorageReader(): ProductDiscontinuedStorageReaderInterface
    {
        return new ProductDiscontinuedStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductDiscontinuedStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductDiscontinuedStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
