<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage;

use Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface;
use Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapper;
use Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapperInterface;
use Spryker\Client\CustomerStorage\Reader\CustomerStorageReader;
use Spryker\Client\CustomerStorage\Reader\CustomerStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerStorage\Reader\CustomerStorageReaderInterface
     */
    public function createCustomerStorageReader(): CustomerStorageReaderInterface
    {
        return new CustomerStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createCustomerStorageMapper(),
        );
    }

    /**
     * @return \Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapperInterface
     */
    public function createCustomerStorageMapper(): CustomerStorageMapperInterface
    {
        return new CustomerStorageMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface
     */
    public function getStorageClient(): CustomerStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CustomerStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CustomerStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CustomerStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CustomerStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
