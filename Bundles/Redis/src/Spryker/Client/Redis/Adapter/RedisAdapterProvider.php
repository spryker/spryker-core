<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Client\Redis\Adapter\Factory\RedisAdapterFactoryInterface;
use Spryker\Client\Redis\Exception\RedisAdapterNotInitializedException;
use Spryker\Shared\StorageRedis\StorageRedisConstants;

class RedisAdapterProvider implements RedisAdapterProviderInterface
{
    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface[]
     */
    protected static $clientPool = [];

    /**
     * @var \Spryker\Client\Redis\Adapter\Factory\RedisAdapterFactoryInterface $clientAdapterFactory
     */
    protected $clientAdapterFactory;

    /**
     * @param \Spryker\Client\Redis\Adapter\Factory\RedisAdapterFactoryInterface $clientFactory
     */
    public function __construct(RedisAdapterFactoryInterface $clientFactory)
    {
        $this->clientAdapterFactory = $clientFactory;
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
     * @throws \Spryker\Client\Redis\Exception\RedisAdapterNotInitializedException
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    public function getAdapter(string $connectionKey): RedisAdapterInterface
    {
        global $config;

        if (!isset(static::$clientPool['user_blocking'])) {
            static::$clientPool['user_blocking'] = $this->createClient(
                (new  RedisConfigurationTransfer())
                    ->setClientOptions([])
                    ->setConnectionCredentials(
                        (new RedisCredentialsTransfer())
                            ->setProtocol($config[StorageRedisConstants::STORAGE_REDIS_PROTOCOL])
                            ->setHost($config[StorageRedisConstants::STORAGE_REDIS_HOST])
                            ->setPort($config[StorageRedisConstants::STORAGE_REDIS_PORT])
                            ->setDatabase(5)
                            ->setPassword($config[StorageRedisConstants::STORAGE_REDIS_PASSWORD])
                            ->setIsPersistent($config[StorageRedisConstants::STORAGE_REDIS_PERSISTENT_CONNECTION])
                    )
                    ->setDataSourceNames([])
            );
        }

        if (!isset(static::$clientPool[$connectionKey])) {
            throw new RedisAdapterNotInitializedException(
                sprintf('Redis client adapter for key %s is not initialized. Call `Spryker\Client\Redis\RedisClient::setupConnection()` first.', $connectionKey)
            );
        }

        return static::$clientPool[$connectionKey];
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function createClient(RedisConfigurationTransfer $configurationTransfer): RedisAdapterInterface
    {
        return $this->clientAdapterFactory->create($configurationTransfer);
    }
}
