<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientInterface;
use Spryker\Client\ProductResourceAliasStorage\Storage\ProductAbstractBulkStorageReader;
use Spryker\Client\ProductResourceAliasStorage\Storage\ProductAbstractBulkStorageReaderInterface;
use Spryker\Client\ProductResourceAliasStorage\Storage\ProductAbstractStorageBySkuReader;
use Spryker\Client\ProductResourceAliasStorage\Storage\ProductConcreteStorageBySkuReader;

class ProductResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Storage\ProductAbstractStorageReaderInterface
     */
    public function createProductAbstractStorageReader()
    {
        return new ProductAbstractStorageBySkuReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Storage\ProductAbstractBulkStorageReaderInterface
     */
    public function createProductAbstractBulkStorageReader(): ProductAbstractBulkStorageReaderInterface
    {
        return new ProductAbstractBulkStorageReader($this->getProductStorageClient());
    }

    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Storage\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorageBySkuReader()
    {
        return new ProductConcreteStorageBySkuReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientInterface
     */
    protected function getProductStorageClient(): ProductResourceAliasStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductResourceAliasStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductResourceAliasStorageDependencyProvider::STORE);
    }
}
