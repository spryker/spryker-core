<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business;

use Predis\Client;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Session\Business\Lock\Redis\RedisSessionLockReader;
use Spryker\Zed\Session\Business\Lock\SessionLockReleaser;
use Spryker\Zed\Session\Business\Lock\SessionLockReleaser\SessionLockReleaserPool;
use Spryker\Zed\Session\Business\Model\SessionFactory;
use Spryker\Zed\Session\SessionDependencyProvider;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 */
class SessionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface
     */
    public function createYvesSessionLockReleaser()
    {
        return $this->createYvesSessionLockReleaserPool()
            ->getLockReleaser($this->getConfig()->getConfiguredSessionHandlerNameYves());
    }

    /**
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaser\SessionLockReleaserPoolInterface
     */
    protected function createYvesSessionLockReleaserPool()
    {
        $sessionLockReleaserPool = new SessionLockReleaserPool();
        $sessionLockReleaserPool->addLockReleaser(
            $this->createRedisSessionLockReleaser(
                $this->getConfig()->getSessionHandlerRedisDataSourceNameYves()
            ),
            SessionConfig::SESSION_HANDLER_REDIS_LOCKING
        );

        return $sessionLockReleaserPool;
    }

    /**
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface
     */
    public function createZedSessionLockReleaser()
    {
        return $this->createZedSessionLockReleaserPool()
            ->getLockReleaser($this->getConfig()->getConfiguredSessionHandlerNameZed());
    }

    /**
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaser\SessionLockReleaserPoolInterface
     */
    protected function createZedSessionLockReleaserPool()
    {
        $sessionLockReleaserPool = new SessionLockReleaserPool();
        $sessionLockReleaserPool->addLockReleaser(
            $this->createRedisSessionLockReleaser(
                $this->getConfig()->getSessionHandlerRedisDataSourceNameZed()
            ),
            SessionConfig::SESSION_HANDLER_REDIS_LOCKING
        );

        return $sessionLockReleaserPool;
    }

    /**
     * @param string $dsn
     *
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface
     */
    protected function createRedisSessionLockReleaser($dsn)
    {
        $redisClient = $this->getRedisClient($dsn);

        return new SessionLockReleaser(
            $this->getRedisSessionLocker($redisClient),
            $this->createRedisSessionLockReader($redisClient)
        );
    }

    /**
     * @param string $dsn
     *
     * @return \Predis\Client
     */
    protected function getRedisClient($dsn)
    {
        return $this
            ->createSessionHandlerFactory()
            ->createRedisClient($dsn);
    }

    /**
     * @param \Predis\Client $redisClient
     *
     * @return \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface
     */
    protected function getRedisSessionLocker(Client $redisClient)
    {
        return $this
            ->createSessionHandlerFactory()
            ->createRedisSpinLockLocker($redisClient);
    }

    /**
     * @return \Spryker\Zed\Session\Business\Model\SessionFactory
     */
    protected function createSessionHandlerFactory()
    {
        return new SessionFactory($this->getMonitoringService());
    }

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionDependencyProvider::MONITORING_SERVICE);
    }

    /**
     * @param \Predis\Client $redisClient
     *
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReaderInterface
     */
    protected function createRedisSessionLockReader(Client $redisClient)
    {
        return new RedisSessionLockReader(
            $redisClient,
            $this->createRedisLockKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface
     */
    protected function createRedisLockKeyGenerator()
    {
        return $this
            ->createSessionHandlerFactory()
            ->createRedisLockKeyGenerator();
    }
}
