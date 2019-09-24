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
use Spryker\Client\StorageDatabase\Storage\Reader\MySqlStorageReader;
use Spryker\Client\StorageDatabase\Storage\Reader\PostgreSqlStorageReader;
use Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderInterface;
use Spryker\Client\StorageDatabase\Storage\StorageDatabase;
use Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolver;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;

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
            $this->getUtilEncodingService(),
            $this->getStorageReaderPlugin()
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
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderInterface
     */
    public function createPostgreSqlStorageReader(): StorageReaderInterface
    {
        return new PostgreSqlStorageReader(
            $this->createConnectionProvider(),
            $this->createStorageTableNameResolver()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderInterface
     */
    public function createMySqlStorageReader(): StorageReaderInterface
    {
        return new MySqlStorageReader(
            $this->createConnectionProvider(),
            $this->createStorageTableNameResolver()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface
     */
    public function getUtilEncodingService(): StorageDatabaseToUtilEncodingInterface
    {
        return $this->getProvidedDependency(StorageDatabaseDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface|null
     */
    public function getStorageReaderPlugin(): ?StorageReaderPluginInterface
    {
        return $this->getProvidedDependency(StorageDatabaseDependencyProvider::PLUGIN_STORAGE_READER_PROVIDER);
    }
}
