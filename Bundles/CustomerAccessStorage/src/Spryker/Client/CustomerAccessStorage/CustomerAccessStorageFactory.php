<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage;

use Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapper;
use Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapperInterface;
use Spryker\Client\CustomerAccessStorage\Storage\CustomerAccessStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerAccessStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerAccessStorage\Storage\CustomerAccessStorageReaderInterface
     */
    public function createCustomerAccessStorageReader()
    {
        return new CustomerAccessStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createCustomerAccessStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapperInterface
     */
    public function createCustomerAccessStorageMapper(): CustomerAccessStorageMapperInterface
    {
        return new CustomerAccessStorageMapper();
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
