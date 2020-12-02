<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerRedis;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlockerRedis\Dependency\Client\SecurityBlockerRedisToRedisClientInterface;
use Spryker\Client\SecurityBlockerRedis\Redis\SecurityBlockerRedisWrapper;
use Spryker\Client\SecurityBlockerRedis\Redis\SecurityBlockerRedisWrapperInterface;

/**
 * @method \Spryker\Client\SecurityBlockerRedis\SecurityBlockerRedisConfig getConfig()
 */
class SecurityBlockerRedisFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlockerRedis\Redis\SecurityBlockerRedisWrapperInterface
     */
    public function createStorageRedisWrapper(): SecurityBlockerRedisWrapperInterface
    {
        return new SecurityBlockerRedisWrapper($this->getRedisClient(), $this->getConfig());
    }

    /**
     * @return \Spryker\Client\SecurityBlockerRedis\Dependency\Client\SecurityBlockerRedisToRedisClientInterface
     */
    public function getRedisClient(): SecurityBlockerRedisToRedisClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerRedisDependencyProvider::CLIENT_REDIS);
    }
}
