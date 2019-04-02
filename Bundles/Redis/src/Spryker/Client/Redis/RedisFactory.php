<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Redis\Connection\Factory\PredisAdapterFactory;
use Spryker\Client\Redis\Connection\Factory\RedisAdapterFactoryInterface;
use Spryker\Client\Redis\Connection\RedisAdapterProvider;
use Spryker\Client\Redis\Connection\RedisAdapterProviderInterface;

class RedisFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Redis\Connection\RedisAdapterProviderInterface
     */
    public function createRedisAdapterProvider(): RedisAdapterProviderInterface
    {
        return new RedisAdapterProvider(
            $this->createRedisAdapterFactory()
        );
    }

    /**
     * @return \Spryker\Client\Redis\Connection\Factory\RedisAdapterFactoryInterface
     */
    public function createRedisAdapterFactory(): RedisAdapterFactoryInterface
    {
        return new PredisAdapterFactory();
    }
}
