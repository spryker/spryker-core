<?php

namespace Spryker\Client\ProductGroupStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductGroupStorage\Storage\ProductGroupStorageReaderInterface;
use Spryker\Client\ProductGroupStorage\Storage\ProductGroupStorageReader;

class ProductGroupStorageFactory extends AbstractFactory
{

    /**
     * @return ProductGroupStorageReaderInterface
     */
    public function createProductGroupStorage()
    {
        return new ProductGroupStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore()
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

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::STORE);
    }
}
