<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Client\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface;
use Spryker\Client\Redis\Client\Adapter\PredisClientAdapter;
use Spryker\Client\Redis\Exception\ConnectionConfigurationException;

class PredisClientAdapterFactory implements ClientAdapterFactoryInterface
{
    protected const CONNECTION_PARAMETERS = 'CONNECTION_PARAMETERS';
    protected const CONNECTION_OPTIONS = 'CONNECTION_OPTIONS';

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Client\Adapter\ClientAdapterInterface
     */
    public function create(RedisConfigurationTransfer $redisConfigurationTransfer): ClientAdapterInterface
    {
        return new PredisClientAdapter(
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
            $this->getConnectionOptions($redisConfigurationTransfer)
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
        $configurationParameters = $redisConfigurationTransfer->getParameters();

        if (isset($configurationParameters[static::CONNECTION_PARAMETERS])) {
            return $configurationParameters[static::CONNECTION_OPTIONS];
        }

        throw new ConnectionConfigurationException('Redis connection parameters are corrupt. Either DSN string or an array of configuration values should be provided.');
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return array|null
     */
    protected function getConnectionOptions(RedisConfigurationTransfer $redisConfigurationTransfer): ?array
    {
        $configurationParameters = $redisConfigurationTransfer->getParameters();

        return isset($configurationParameters[static::CONNECTION_OPTIONS]) ? (array)$configurationParameters[static::CONNECTION_OPTIONS] : null;
    }
}
