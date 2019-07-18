<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication;

use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Shared\Session\Model\SessionStorage;
use Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPool;
use Spryker\Shared\Session\Model\SessionStorage\SessionStorageOptions;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Session\SessionDependencyProvider;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SessionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Session\Model\SessionStorageInterface
     */
    public function createSessionStorage()
    {
        return new SessionStorage(
            $this->createSessionStorageOptions(),
            $this->createSessionStorageHandlerPool(),
            $this->getConfig()->getConfiguredSessionHandlerNameZed()
        );
    }

    /**
     * @return \Spryker\Shared\Session\Model\SessionStorage\SessionStorageOptionsInterface
     */
    protected function createSessionStorageOptions()
    {
        return new SessionStorageOptions($this->getConfig()->getSessionStorageOptions());
    }

    /**
     * @return \Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPoolInterface
     */
    protected function createSessionStorageHandlerPool()
    {
        $sessionHandlerPool = new SessionStorageHandlerPool(
            $this->getSessionHandlerPlugins()
        );

        /**
         * This check was added because of BC and will be removed in the next major release.
         */
        if (!$this->getSessionHandlerPlugins()) {
            $sessionHandlerPool
                ->addHandler($this->createSessionHandlerRedis(), SessionConfig::SESSION_HANDLER_REDIS)
                ->addHandler($this->createSessionHandlerRedisLocking(), SessionConfig::SESSION_HANDLER_REDIS_LOCKING)
                ->addHandler($this->createSessionHandlerFile(), SessionConfig::SESSION_HANDLER_FILE);
        }

        return $sessionHandlerPool;
    }

    /**
     * @deprecated Use `Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface` instead.
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedis|\SessionHandlerInterface
     */
    protected function createSessionHandlerRedis()
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerRedis(
            $this->getConfig()->getSessionHandlerRedisConnectionParametersZed(),
            $this->getConfig()->getSessionHandlerRedisConnectionOptionsZed()
        );
    }

    /**
     * @deprecated Use `Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory::createSessionHandlerRedisLocking()` instead.
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking|\SessionHandlerInterface
     */
    protected function createSessionHandlerRedisLocking()
    {
        return $this->createSessionHandlerFactory()->createRedisLockingSessionHandler(
            $this->getConfig()->getSessionHandlerRedisConnectionParametersZed(),
            $this->getConfig()->getSessionHandlerRedisConnectionOptionsZed()
        );
    }

    /**
     * @deprecated Use `Spryker\Zed\SessionFile\Communication\SessionFileCommunicationFactory::createSessionHandlerFile()` instead.
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking|\SessionHandlerInterface
     */
    protected function createSessionHandlerFile()
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerFile(
            $this->getConfig()->getSessionHandlerFileSavePath()
        );
    }

    /**
     * @deprecated User `Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory::createSessionHandlerFactory()` instead.
     *
     * @return \Spryker\Zed\Session\Communication\SessionHandlerFactory
     */
    protected function createSessionHandlerFactory()
    {
        return new SessionHandlerFactory(
            $this->getConfig()->getSessionLifeTime(),
            $this->getMonitoringService()
        );
    }

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionDependencyProvider::MONITORING_SERVICE);
    }

    /**
     * @return \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface[]
     */
    protected function getSessionHandlerPlugins(): array
    {
        return $this->getProvidedDependency(SessionDependencyProvider::PLUGINS_SESSION_HANDLER);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient()
    {
        return $this->getProvidedDependency(SessionDependencyProvider::SESSION_CLIENT);
    }
}
