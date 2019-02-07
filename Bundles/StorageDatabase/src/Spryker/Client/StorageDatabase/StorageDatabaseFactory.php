<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProvider;
use Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\Database\StorageDatabase;
use Spryker\Client\StorageDatabase\Database\StorageDatabaseInterface;
use Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolver;
use Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolverInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseConfig getConfig()
 */
class StorageDatabaseFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface
     */
    public function createConnectionProvider(): ConnectionProviderInterface
    {
        return new ConnectionProvider(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Database\StorageDatabaseInterface
     */
    public function createStorageDatabaseService(): StorageDatabaseInterface
    {
        return new StorageDatabase(
            $this->createConnectionProvider(),
            $this->createResourceKeyToTableNameResolver()
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolverInterface
     */
    public function createResourceKeyToTableNameResolver(): ResourceKeyToTableNameResolverInterface
    {
        return new ResourceKeyToTableNameResolver(
            $this->getConfig()
        );
    }
}
