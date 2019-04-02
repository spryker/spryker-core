<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Connection\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Predis\Client;
use Spryker\Client\Redis\Connection\Adapter\PredisAdapter;
use Spryker\Client\Redis\Connection\Adapter\RedisAdapterInterface;
use Spryker\Client\Redis\Exception\ConnectionConfigurationException;

class PredisAdapterFactory implements RedisAdapterFactoryInterface
{
    protected const CONNECTION_PARAMETERS = 'CONNECTION_PARAMETERS';
    protected const CONNECTION_OPTIONS = 'CONNECTION_OPTIONS';

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Connection\Adapter\RedisAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        return new PredisAdapter(
            $this->createPredisClient($redisConfigurationTransfer)
        );
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
            $redisConfigurationTransfer->getConnectionOptions()
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

        if (isset($connectionCredentials[RedisCredentialsTransfer::PASSWORD]) && !$connectionCredentials[RedisCredentialsTransfer::PASSWORD]) {
            unset($connectionCredentials[RedisCredentialsTransfer::PASSWORD]);
        }

        return $connectionCredentials;
    }
}
