<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Redis\Adapter\Factory\PredisAdapterFactory;
use Spryker\Client\Redis\Adapter\Factory\RedisAdapterFactoryInterface;
use Spryker\Client\Redis\Adapter\RedisAdapterProvider;
use Spryker\Client\Redis\Adapter\RedisAdapterProviderInterface;

class RedisFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterProviderInterface
     */
    public function createRedisAdapterProvider(): RedisAdapterProviderInterface
    {
        return new RedisAdapterProvider(
            $this->createRedisAdapterFactory()
        );
    }

    /**
     * @return \Spryker\Client\Redis\Adapter\Factory\RedisAdapterFactoryInterface
     */
    public function createRedisAdapterFactory(): RedisAdapterFactoryInterface
    {
        return new PredisAdapterFactory();
    }
}
