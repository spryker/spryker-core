<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Connection;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use Spryker\Client\Redis\Exception\ConnectionConfigurationException;
use Spryker\Client\Redis\Exception\ConnectionNotInitializedException;

class ConnectionProvider implements ConnectionProviderInterface
{
    /**
     * @var \Predis\Client[]
     */
    protected static $connectionPool = [];

    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void
    {
        if (isset(static::$connectionPool[$connectionKey])) {
            return;
        }

        static::$connectionPool[$connectionKey] = $this->createConnection($configurationTransfer);
    }

    /**
     * @param string $connectionKey
     *
     * @throws \Spryker\Client\Redis\Exception\ConnectionNotInitializedException
     *
     * @return \Predis\Client
     */
    public function getConnection(string $connectionKey): Client
    {
        if (!isset(static::$connectionPool[$connectionKey])) {
            throw new ConnectionNotInitializedException(
                sprintf('Connection to Redis for key %s is not initialized. Call `Spryker\Client\Redis\RedisClient::setupConnection()` first.', $connectionKey)
            );
        }

        return static::$connectionPool[$connectionKey];
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return \Predis\Client
     */
    protected function createConnection(RedisConfigurationTransfer $configurationTransfer): Client
    {
        return new Client(
            $this->getConnectionParameters($configurationTransfer),
            $configurationTransfer->getConnectionOptions()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @throws \Spryker\Client\Redis\Exception\ConnectionConfigurationException
     *
     * @return array|string
     */
    protected function getConnectionParameters(RedisConfigurationTransfer $redisConfigurationTransfer)
    {
        if ($redisConfigurationTransfer->getConnectionParameters()) {
            return $redisConfigurationTransfer->getConnectionParameters();
        }

        if (!empty($redisConfigurationTransfer->getDataSourceName())) {
            return $redisConfigurationTransfer->getDataSourceName();
        }

        throw new ConnectionConfigurationException('Redis connection parameters are empty. Either DSN string or an array of configuration values should be provided.');
    }
}
