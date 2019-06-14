<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

use Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\StorageDatabaseConfig;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;

class StorageReaderFactory implements StorageReaderFactoryInterface
{
    /**
     * @var \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface
     */
    protected $connectionProvider;

    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface
     */
    protected $storageTableNameResolver;

    /**
     * @param \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface $connectionProvider
     * @param \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface $storageTableNameResolver
     * @param \Spryker\Client\StorageDatabase\StorageDatabaseConfig $config
     */
    public function __construct(
        ConnectionProviderInterface $connectionProvider,
        StorageTableNameResolverInterface $storageTableNameResolver,
        StorageDatabaseConfig $config
    ) {
        $this->connectionProvider = $connectionProvider;
        $this->storageTableNameResolver = $storageTableNameResolver;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader
     */
    public function createStorageReader(): AbstractStorageReader
    {
        if ($this->config->isPostgresSqlDbEngine()) {
            return new PostgreSqlStorageReader(
                $this->connectionProvider,
                $this->storageTableNameResolver
            );
        }

        if ($this->config->isMySqlDbEngine()) {
            return new MySqlStorageReader(
                $this->connectionProvider,
                $this->storageTableNameResolver
            );
        }
    }
}
