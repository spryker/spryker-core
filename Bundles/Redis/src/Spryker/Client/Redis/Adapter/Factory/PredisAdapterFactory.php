<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Predis\Client;
use Spryker\Client\Redis\Adapter\LoggableRedisAdapter;
use Spryker\Client\Redis\Adapter\PredisAdapter;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;
use Spryker\Client\Redis\Exception\ConnectionConfigurationException;
use Spryker\Client\Redis\RedisConfig;
use Spryker\Shared\Redis\Logger\RedisLoggerInterface;

class PredisAdapterFactory implements RedisAdapterFactoryInterface
{
    protected const CONNECTION_PARAMETERS = 'CONNECTION_PARAMETERS';
    protected const CONNECTION_OPTIONS = 'CONNECTION_OPTIONS';

    /**
     * @var \Spryker\Client\Redis\RedisConfig
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    protected $redisLogger;

    /**
     * @param \Spryker\Client\Redis\RedisConfig $config
     * @param \Spryker\Shared\Redis\Logger\RedisLoggerInterface $redisLogger
     */
    public function __construct(RedisConfig $config, RedisLoggerInterface $redisLogger)
    {
        $this->config = $config;
        $this->redisLogger = $redisLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        $predisAdapter = new PredisAdapter(
            $this->createPredisClient($redisConfigurationTransfer)
        );

        if (!$this->config->isDevelopmentMode()) {
            return $predisAdapter;
        }

        return $this->createLoggablePredisAdapter($predisAdapter, $redisConfigurationTransfer);
    }

    /**
     * @param \Spryker\Client\Redis\Adapter\RedisAdapterInterface $redisAdapter
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    public function createLoggablePredisAdapter(
        RedisAdapterInterface $redisAdapter,
        RedisConfigurationTransfer $redisConfigurationTransfer
    ): RedisAdapterInterface {
        return new LoggableRedisAdapter($redisConfigurationTransfer, $redisAdapter, $this->redisLogger);
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Predis\Client
     */
    protected function createPredisClient(RedisConfigurationTransfer $redisConfigurationTransfer): Client
    {
        return new Client(
            $this->getConnectionParameters($redisConfigurationTransfer),
            $redisConfigurationTransfer->getClientOptions()
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
        $configurationParameters = $redisConfigurationTransfer->getDataSourceNames();

        if (!$configurationParameters) {
            $configurationParameters = $this->getFilteredConnectionCredentials($redisConfigurationTransfer);
        }

        if ($configurationParameters) {
            return $configurationParameters;
        }

        throw new ConnectionConfigurationException('Redis connection parameters are corrupt. Either DSN string or an array of configuration values should be provided.');
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return array
     */
    protected function getFilteredConnectionCredentials(RedisConfigurationTransfer $redisConfigurationTransfer): array
    {
        $connectionCredentialsTransfer = $redisConfigurationTransfer->getConnectionCredentials();

        if (!$connectionCredentialsTransfer) {
            return [];
        }

        $connectionCredentials = $connectionCredentialsTransfer->toArray();
        $connectionCredentials = $this->clearEmptyPassword($connectionCredentials);

        return $connectionCredentials;
    }

    /**
     * @param array $connectionCredentials
     *
     * @return array
     */
    protected function clearEmptyPassword(array $connectionCredentials): array
    {
        if (isset($connectionCredentials[RedisCredentialsTransfer::PASSWORD]) && !$connectionCredentials[RedisCredentialsTransfer::PASSWORD]) {
            unset($connectionCredentials[RedisCredentialsTransfer::PASSWORD]);
        }

        return $connectionCredentials;
    }
}
