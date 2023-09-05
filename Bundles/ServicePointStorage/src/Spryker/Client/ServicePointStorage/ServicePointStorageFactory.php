<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface;
use Spryker\Client\ServicePointStorage\Generator\StorageKeyGenerator;
use Spryker\Client\ServicePointStorage\Generator\StorageKeyGeneratorInterface;
use Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapper;
use Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface;
use Spryker\Client\ServicePointStorage\Reader\ServicePointStorageReader;
use Spryker\Client\ServicePointStorage\Reader\ServicePointStorageReaderInterface;
use Spryker\Client\ServicePointStorage\Reader\ServiceTypeStorageReader;
use Spryker\Client\ServicePointStorage\Reader\ServiceTypeStorageReaderInterface;

class ServicePointStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ServicePointStorage\Reader\ServicePointStorageReaderInterface
     */
    public function createServicePointStorageReader(): ServicePointStorageReaderInterface
    {
        return new ServicePointStorageReader(
            $this->getStorageClient(),
            $this->createStorageKeyGenerator(),
            $this->getUtilEncodingService(),
            $this->createServicePointStorageMapper(),
        );
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Reader\ServiceTypeStorageReaderInterface
     */
    public function createServiceTypeStorageReader(): ServiceTypeStorageReaderInterface
    {
        return new ServiceTypeStorageReader(
            $this->getStorageClient(),
            $this->createStorageKeyGenerator(),
            $this->getUtilEncodingService(),
            $this->createServicePointStorageMapper(),
        );
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Generator\StorageKeyGeneratorInterface
     */
    public function createStorageKeyGenerator(): StorageKeyGeneratorInterface
    {
        return new StorageKeyGenerator(
            $this->getSynchronizationService(),
        );
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface
     */
    public function createServicePointStorageMapper(): ServicePointStorageMapperInterface
    {
        return new ServicePointStorageMapper();
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface
     */
    public function getStorageClient(): ServicePointStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ServicePointStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ServicePointStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ServicePointStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ServicePointStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
