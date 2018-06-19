<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface;
use Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReader;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface;

class ProductAlternativeStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface
     */
    public function createProductAlternativeStorageReader(): ProductAlternativeStorageReaderInterface
    {
        return new ProductAlternativeStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }
    
    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductAlternativeStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductAlternativeStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
