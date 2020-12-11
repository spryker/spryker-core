<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface;
use Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapper;
use Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface;

/**
 * @method \Spryker\Client\SecurityBlocker\SecurityBlockerConfig getConfig()
 */
class SecurityBlockerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface
     */
    public function createSecurityBlockerRedisWrapper(): SecurityBlockerRedisWrapperInterface
    {
        return new SecurityBlockerRedisWrapper($this->getRedisClient(), $this->getConfig());
    }

    /**
     * @return \Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface
     */
    public function getRedisClient(): SecurityBlockerToRedisClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerDependencyProvider::CLIENT_REDIS);
    }
}
