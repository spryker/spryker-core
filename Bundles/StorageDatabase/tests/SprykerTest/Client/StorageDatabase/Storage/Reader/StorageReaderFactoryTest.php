<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase\Storage\Reader;

use Codeception\Test\Unit;
use Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\Storage\Reader\MySqlStorageReader;
use Spryker\Client\StorageDatabase\Storage\Reader\PostgreSqlStorageReader;
use Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderFactory;
use Spryker\Client\StorageDatabase\StorageDatabaseConfig;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group Storage
 * @group Reader
 * @group StorageReaderFactoryTest
 * Add your own group annotations below this line
 */
class StorageReaderFactoryTest extends Unit
{
    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storageConfigMock;

    /**
     * @var \Spryker\Client\StorageDatabase\Storage\Reader\StorageReaderFactoryInterface
     */
    protected $storageReaderFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupStorageConfigMock();
        $this->setupStorageReaderFactory();
    }

    /**
     * @return void
     */
    protected function testCanCreatePostgreSqlStorageReader(): void
    {
        $this->storageConfigMock->method('isPostgresSqlDbEngine')->willReturn(true);
        $this->storageConfigMock->method('isMySqlDbEngine')->willReturn(false);

        $this->assertInstanceOf(
            PostgreSqlStorageReader::class,
            $this->storageReaderFactory->createStorageReader()
        );
    }

    /**
     * @return void
     */
    protected function testCanCreateMySqlStorageReader(): void
    {
        $this->storageConfigMock->method('isPostgresSqlDbEngine')->willReturn(false);
        $this->storageConfigMock->method('isMySqlDbEngine')->willReturn(true);

        $this->assertInstanceOf(
            MySqlStorageReader::class,
            $this->storageReaderFactory->createStorageReader()
        );
    }

    /**
     * @return void
     */
    protected function setupStorageConfigMock(): void
    {
        $this->storageConfigMock = $this->createMock(StorageDatabaseConfig::class);
    }

    /**
     * @return void
     */
    protected function setupStorageReaderFactory(): void
    {
        /** @var \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface|\PHPUnit\Framework\MockObject\MockObject $connectionProviderMock */
        $connectionProviderMock = $this->createMock(ConnectionProviderInterface::class);

        /** @var \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface|\PHPUnit\Framework\MockObject\MockObject $storageTableNameResolverMock */
        $storageTableNameResolverMock = $this->createMock(StorageTableNameResolverInterface::class);

        $this->storageReaderFactory = new StorageReaderFactory(
            $connectionProviderMock,
            $storageTableNameResolverMock,
            $this->storageConfigMock
        );
    }
}
