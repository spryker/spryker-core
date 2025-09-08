<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Lock\Business\LockFactory\LockFactory;
use Spryker\Zed\Lock\Business\LockFactory\LockFactoryInterface;
use Spryker\Zed\Lock\Business\LockMechanism\LockMechanism;
use Spryker\Zed\Lock\Business\LockMechanism\LockMechanismInterface;
use Spryker\Zed\Lock\Business\PersistingStore\RedisStore;
use Spryker\Zed\Lock\Dependency\Client\LockToStorageRedisClientInterface;
use Spryker\Zed\Lock\LockDependencyProvider;
use Symfony\Component\Lock\PersistingStoreInterface;

/**
 * @method \Spryker\Zed\Lock\LockConfig getConfig()
 */
class LockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var \Spryker\Zed\Lock\Business\LockMechanism\LockMechanismInterface|null
     */
    protected ?LockMechanismInterface $lockMechanism = null;

    /**
     * @return \Spryker\Zed\Lock\Business\LockMechanism\LockMechanismInterface
     */
    public function createLockMechanism(): LockMechanismInterface
    {
        if ($this->lockMechanism === null) {
            $this->lockMechanism = new LockMechanism($this->createLockFactory());
        }

        return $this->lockMechanism;
    }

    /**
     * @return \Spryker\Zed\Lock\Business\LockFactory\LockFactoryInterface
     */
    public function createLockFactory(): LockFactoryInterface
    {
        return new LockFactory($this->createDefaultStorage());
    }

    /**
     * @return \Symfony\Component\Lock\PersistingStoreInterface
     */
    public function createDefaultStorage(): PersistingStoreInterface
    {
        return new RedisStore($this->getStorageRedisClient(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Lock\Dependency\Client\LockToStorageRedisClientInterface
     */
    public function getStorageRedisClient(): LockToStorageRedisClientInterface
    {
        return $this->getProvidedDependency(LockDependencyProvider::CLIENT_STORAGE_REDIS);
    }
}
