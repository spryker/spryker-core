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
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Client\Redis\RedisConfig getConfig()
 */
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
        return new PredisAdapterFactory(
            $this->getConfig(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): RedisToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(RedisDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
