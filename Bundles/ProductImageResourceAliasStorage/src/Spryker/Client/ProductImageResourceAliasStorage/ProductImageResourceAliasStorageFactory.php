<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageResourceAliasStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductImageResourceAliasStorage\Storage\ProductAbstractImageStorageReader;
use Spryker\Client\ProductImageResourceAliasStorage\Storage\ProductConcreteImageStorageReader;

class ProductImageResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductImageResourceAliasStorage\Storage\ProductAbstractImageStorageReaderInterface
     */
    public function createProductAbstractImageStorageReader()
    {
        return new ProductAbstractImageStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductImageResourceAliasStorage\Storage\ProductConcreteImageStorageReaderInterface
     */
    public function createProductConcreteImageStorageReader()
    {
        return new ProductConcreteImageStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Client\ProductImageResourceAliasStorageToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Service\ProductImageResourceAliasStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
