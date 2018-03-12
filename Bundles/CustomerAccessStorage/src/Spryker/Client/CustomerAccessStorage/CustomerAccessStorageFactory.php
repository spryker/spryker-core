<?php

namespace Spryker\Client\CustomerAccessStorage;

use Spryker\Client\CustomerAccessStorage\Storage\CustomerAccessStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerAccessStorageFactory extends AbstractFactory
{
    /**
     *
     * @return \Spryker\Client\CustomerAccessStorage\Storage\CustomerAccessStorageReaderInterface
     */
    public function createCustomerAccessStorageReader()
    {
        return new CustomerAccessStorageReader($this->getStorageClient(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(CustomerAccessStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(CustomerAccessStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
