<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Redis;

use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;
use Spryker\Shared\Redis\Logger\RedisInMemoryLogger;
use Spryker\Shared\Redis\Logger\RedisLoggerInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Redis\WebProfiler\RedisDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class RedisFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollector
     */
    public function createRedisDataCollector(): DataCollector
    {
        return new RedisDataCollector(
            $this->createRedisLogger()
        );
    }

    /**
     * @return \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    public function createRedisLogger(): RedisLoggerInterface
    {
        return new RedisInMemoryLogger(
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
