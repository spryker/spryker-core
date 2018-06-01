<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroupStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductGroupStorage\Storage\ProductGroupStorageReader;

class ProductGroupStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductGroupStorage\Storage\ProductGroupStorageReaderInterface
     */
    public function createProductGroupStorage()
    {
        return new ProductGroupStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductGroupStorage\Dependency\Client\ProductGroupStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductGroupStorage\Dependency\Service\ProductGroupStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
