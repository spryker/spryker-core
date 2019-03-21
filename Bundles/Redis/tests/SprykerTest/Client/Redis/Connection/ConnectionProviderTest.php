<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis\Connection;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use ReflectionProperty;
use Spryker\Client\Redis\Connection\ConnectionProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Redis
 * @group Connection
 * @group ConnectionProviderTest
 * Add your own group annotations below this line
 */
class ConnectionProviderTest extends Unit
{
    protected const CONNECTION_KEY_SESSION = 'session connection key';
    protected const CONNECTION_KEY_STORAGE = 'storage connection key';

    /**
     * @var \Spryker\Client\Redis\Connection\ConnectionProviderInterface
     */
    protected $connectionProvider;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->connectionProvider = new ConnectionProvider();
    }

    /**
     * @expectedException \Spryker\Client\Redis\Exception\ConnectionNotInitializedException
     *
     * @return void
     */
    public function testThrowsExceptionWhenConnectionNotInitialized(): void
    {
        $this->resetConnectionPool();

        $this->connectionProvider->getConnection(static::CONNECTION_KEY_SESSION);
    }

    /**
     * @expectedException \Spryker\Client\Redis\Exception\ConnectionConfigurationException
     *
     * @return void
     */
    public function testThrowsExceptionWhenReinitializingConnection(): void
    {
        $this->resetConnectionPool();

        $configurationTransfer = $this->getRedisConfigurationTransfer('');
        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $configurationTransfer);
        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $configurationTransfer);
    }

    /**
     * @return void
     */
    public function testCanSetUpNewConnection(): void
    {
        $this->resetConnectionPool();

        $configurationTransfer = $this->getRedisConfigurationTransfer();
        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $configurationTransfer);
        $connection = $this->connectionProvider->getConnection(static::CONNECTION_KEY_SESSION);

        $this->assertInstanceOf(Client::class, $connection);
    }

    /**
     * @return void
     */
    public function testCanPrepareDifferentConnectionsForDifferentKeys(): void
    {
        $this->resetConnectionPool();

        $sessionConfigurationTransfer = $this->getRedisConfigurationTransfer();
        $storageConfigurationTransfer = $this->getRedisConfigurationTransfer();

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $sessionConfigurationTransfer);
        $sessionConnection = $this->connectionProvider->getConnection(static::CONNECTION_KEY_SESSION);

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_STORAGE, $storageConfigurationTransfer);
        $storageConnection = $this->connectionProvider->getConnection(static::CONNECTION_KEY_STORAGE);

        $this->assertNotSame($sessionConnection, $storageConnection);
    }

    /**
     * @param string $dsnString
     * @param array $connectionParameters
     * @param array $connectionOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\RedisConfigurationTransfer
     */
    protected function getRedisConfigurationTransfer(string $dsnString = 'dsn', array $connectionParameters = [], array $connectionOptions = []): AbstractTransfer
    {
        return (new RedisConfigurationTransfer())
            ->setDataSourceName($dsnString)
            ->setConnectionParameters($connectionParameters)
            ->setConnectionOptions($connectionOptions);
    }

    /**
     * @return void
     */
    protected function resetConnectionPool(): void
    {
        $connectionPoolReflection = new ReflectionProperty(ConnectionProvider::class, 'connectionPool');
        $connectionPoolReflection->setAccessible(true);
        $connectionPoolReflection->setValue(null, []);
    }
}
