<?php

namespace Spryker\Client\ProductCategoryFilterStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryFilterStorage\Storage\ProductCategoryFilterStorageReader;
use Spryker\Shared\Kernel\Store;

class ProductCategoryFilterStorageFactory extends AbstractFactory
{

    /**
     * @return ProductCategoryFilterStorageReader
     */
    public function createProductCategoryFilterStorageReader()
    {
        return new ProductCategoryFilterStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\ProductCategoryFilterStorage\Dependency\Client\ProductCategoryFilterStorageToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::STORE);
    }
}
