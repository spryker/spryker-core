<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Client;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface;
use Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface;
use Spryker\Client\Redis\Exception\ConnectionNotInitializedException;

class ClientProvider implements ClientProviderInterface
{
    /**
     * @var \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface[]
     */
    protected static $clientPool = [];

    /**
     * @var \Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface $clientFactory
     */
    private $clientFactory;

    /**
     * @param \Spryker\Client\Redis\Client\Factory\ClientAdapterFactoryInterface $clientFactory
     */
    public function __construct(ClientAdapterFactoryInterface $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void
    {
        if (isset(static::$clientPool[$connectionKey])) {
            return;
        }

        static::$clientPool[$connectionKey] = $this->createClient($configurationTransfer);
    }

    /**
     * @param string $connectionKey
     *
     * @throws \Spryker\Client\Redis\Exception\ConnectionNotInitializedException
     *
     * @return \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface
     */
    public function getClient(string $connectionKey): ClientAdapterInterface
    {
        if (!isset(static::$clientPool[$connectionKey])) {
            throw new ConnectionNotInitializedException(
                sprintf('Redis connection for key %s is not initialized. Call `Spryker\Client\Redis\RedisClient::setupConnection()` first.', $connectionKey)
            );
        }

        return static::$clientPool[$connectionKey];
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface
     */
    protected function createClient(RedisConfigurationTransfer $configurationTransfer): ClientAdapterInterface
    {
        return $this->clientFactory->create($configurationTransfer);
    }
}
