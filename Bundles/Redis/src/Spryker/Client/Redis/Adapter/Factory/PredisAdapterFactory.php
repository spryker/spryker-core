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
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;
use Spryker\Shared\Redis\Logger\RedisInMemoryLogger;
use Spryker\Shared\Redis\Logger\RedisLoggerInterface;

class PredisAdapterFactory implements RedisAdapterFactoryInterface
{
    protected const CONNECTION_PARAMETERS = 'CONNECTION_PARAMETERS';
    protected const CONNECTION_OPTIONS = 'CONNECTION_OPTIONS';

    /**
     * @var \Spryker\Client\Redis\RedisConfig
     */
    protected $redisConfig;

    /**
     * @var \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\Redis\RedisConfig $redisConfig
     * @param \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(RedisConfig $redisConfig, RedisToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->redisConfig = $redisConfig;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        if (!$this->redisConfig->isDevelopmentMode()) {
            return $this->createPredisAdapter($redisConfigurationTransfer);
        }

        return $this->createLoggablePredisAdapter($redisConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function createPredisAdapter(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        return new PredisAdapter(
            $this->createPredisClient($redisConfigurationTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function createLoggablePredisAdapter(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        return new LoggableRedisAdapter(
            $this->createPredisAdapter($redisConfigurationTransfer),
            $this->createRedisLogger($redisConfigurationTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    protected function createRedisLogger(RedisConfigurationTransfer $redisConfigurationTransfer): RedisLoggerInterface
    {
        return new RedisInMemoryLogger($this->utilEncodingService, $redisConfigurationTransfer);
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
        $connectionCredentials = $this->clearEmptySchema($connectionCredentials);

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

    /**
     * @param array $connectionCredentials
     *
     * @return array
     */
    protected function clearEmptySchema(array $connectionCredentials): array
    {
        if (isset($connectionCredentials[RedisCredentialsTransfer::SCHEME]) && !$connectionCredentials[RedisCredentialsTransfer::SCHEME]) {
            unset($connectionCredentials[RedisCredentialsTransfer::SCHEME]);
        }

        return $connectionCredentials;
    }
}
