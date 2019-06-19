<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\StorageDatabase\Connection\ConnectionProvider;
use Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface;
use Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader;
use Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderFactory;
use Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderFactoryInterface;
use Spryker\Client\StorageDatabase\Storage\StorageDatabase;
use Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolver;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseConfig getConfig()
 */
class StorageDatabaseFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface
     */
    public function createConnectionProvider(): ConnectionProviderInterface
    {
        return new ConnectionProvider(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface
     */
    public function createStorageDatabaseService(): StorageDatabaseInterface
    {
        return new StorageDatabase(
            $this->createStorageReader(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface
     */
    public function createStorageTableNameResolver(): StorageTableNameResolverInterface
    {
        return new StorageTableNameResolver(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader
     */
    public function createStorageReader(): AbstractStorageReader
    {
        return $this->createStorageReaderFactory()->createStorageReader();
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderFactoryInterface
     */
    public function createStorageReaderFactory(): StorageReaderFactoryInterface
    {
        return new StorageReaderFactory(
            $this->createConnectionProvider(),
            $this->createStorageTableNameResolver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface
     */
    public function getUtilEncodingService(): StorageDatabaseToUtilEncodingInterface
    {
        return $this->getProvidedDependency(StorageDatabaseDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
