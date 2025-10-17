<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter\Factory;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;
use Spryker\Client\Redis\Adapter\VersionAgnosticPredisAdapter;

class PredisAdapterFactory extends AbstractRedisAdapterFactory
{
    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function createVersionAgnosticAdapter(RedisConfigurationTransfer $redisConfigurationTransfer): RedisAdapterInterface
    {
        return new VersionAgnosticPredisAdapter(
            $this->createPredisClient($redisConfigurationTransfer),
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
            $redisConfigurationTransfer->getClientOptions(),
        );
    }
}
