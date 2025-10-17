<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Redis;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;
use Spryker\Client\Redis\Adapter\VersionAgnosticPhpredisAdapter;
use Spryker\Client\Redis\Exception\ConnectionConfigurationException;

class PhpredisAdapterFactory extends AbstractRedisAdapterFactory
{
    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function createVersionAgnosticAdapter(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        return new VersionAgnosticPhpredisAdapter(
            $this->createPhpredisClient($redisConfigurationTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Redis
     */
    protected function createPhpredisClient(RedisConfigurationTransfer $redisConfigurationTransfer): Redis
    {
        $options = [];

        $connectionParameters = $this->getConnectionParameters($redisConfigurationTransfer);

        $options['host'] = $connectionParameters['host'];
        $options['port'] = (int)$connectionParameters['port'];

        $redis = new Redis($options); //@phpstan-ignore-line

        if (isset($connectionParameters['database'])) {
            $redis->select($connectionParameters['database']);
        }

        return $redis;
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @throws \Spryker\Client\Redis\Exception\ConnectionConfigurationException
     *
     * @return array<string, mixed>
     */
    protected function getConnectionParameters(RedisConfigurationTransfer $redisConfigurationTransfer): array
    {
        $connectionParameters = parent::getConnectionParameters($redisConfigurationTransfer);

        if (!isset($connectionParameters['host'])) {
            throw new ConnectionConfigurationException('Redis host is not set.');
        }

        if (!isset($connectionParameters['port'])) {
            throw new ConnectionConfigurationException('Redis port is not set.');
        }

        return $connectionParameters;
    }
}
