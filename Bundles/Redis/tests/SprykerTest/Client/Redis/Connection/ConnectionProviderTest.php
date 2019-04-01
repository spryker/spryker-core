<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis\Connection;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use ReflectionProperty;
use Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface;
use Spryker\Client\Redis\Client\ClientProvider;
use Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface;

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
     * @var \Spryker\Client\Redis\Client\ClientProviderInterface
     */
    protected $connectionProvider;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->connectionProvider = new ClientProvider(
            $this->createClientAdapterFactoryMock()
        );
    }

    /**
     * @expectedException \Spryker\Client\Redis\Exception\ConnectionNotInitializedException
     *
     * @return void
     */
    public function testThrowsExceptionWhenConnectionNotInitialized(): void
    {
        $this->resetConnectionPool();

        $this->connectionProvider->getClient(static::CONNECTION_KEY_SESSION);
    }

    /**
     * @return void
     */
    public function testCanSetUpNewConnection(): void
    {
        $this->resetConnectionPool();

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, new RedisConfigurationTransfer());
        $connection = $this->connectionProvider->getClient(static::CONNECTION_KEY_SESSION);

        $this->assertInstanceOf(ClientAdapterInterface::class, $connection);
    }

    /**
     * @return void
     */
    public function testCanPrepareDifferentConnectionsForDifferentConnectionKeys(): void
    {
        $this->resetConnectionPool();

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, new RedisConfigurationTransfer());
        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_STORAGE, new RedisConfigurationTransfer());

        $sessionConnection = $this->connectionProvider->getClient(static::CONNECTION_KEY_SESSION);
        $storageConnection = $this->connectionProvider->getClient(static::CONNECTION_KEY_STORAGE);

        $this->assertNotSame($sessionConnection, $storageConnection);
    }

    /**
     * @return void
     */
    public function testDoesNotSetUpNewConnectionForTheSameConnectionKey(): void
    {
        $this->resetConnectionPool();

        $configurationTransfer = new RedisConfigurationTransfer();

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $configurationTransfer);
        $connection1 = $this->connectionProvider->getClient(static::CONNECTION_KEY_SESSION);

        $this->connectionProvider->setupConnection(static::CONNECTION_KEY_SESSION, $configurationTransfer);

        $connection2 = $this->connectionProvider->getClient(static::CONNECTION_KEY_SESSION);

        $this->assertSame($connection1, $connection2);
    }

    /**
     * @return void
     */
    protected function resetConnectionPool(): void
    {
        $connectionPoolReflection = new ReflectionProperty(ClientProvider::class, 'clientPool');
        $connectionPoolReflection->setAccessible(true);
        $connectionPoolReflection->setValue(null, []);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface
     */
    protected function createClientAdapterFactoryMock()
    {
        $clientAdapterFactory = ($this->createMock(ClientAdapterFactoryInterface::class));
        $clientAdapterFactory->method('create')
            ->willReturnCallback(function () {
                    return $this->createMock(ClientAdapterInterface::class);
            });

        return $clientAdapterFactory;
    }
}
