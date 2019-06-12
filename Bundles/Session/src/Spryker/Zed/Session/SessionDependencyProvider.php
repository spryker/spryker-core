<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session;

use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 */
class SessionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SESSION_CLIENT = 'SESSION_CLIENT';
    public const MONITORING_SERVICE = 'monitoring service';
    public const PLUGINS_SESSION_HANDLER = 'PLUGINS_SESSION_HANDLER';
    public const PLUGINS_YVES_SESSION_LOCK_RELEASER = 'PLUGINS_YVES_SESSION_LOCK_RELEASER';
    public const PLUGINS_ZED_SESSION_LOCK_RELEASER = 'PLUGINS_ZED_SESSION_LOCK_RELEASER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addSessionClient($container);
        $container = $this->addMonitoringService($container);
        $container = $this->addSessionHandlerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSessionClient($container);
        $container = $this->addMonitoringService($container);
        $container = $this->addYvesSessionLockReleaserPlugins($container);
        $container = $this->addZedSessionLockReleaserPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::SESSION_CLIENT] = function () use ($container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMonitoringService(Container $container)
    {
        $container[static::MONITORING_SERVICE] = function () use ($container) {
            $sessionToMonitoringServiceBridge = new SessionToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service()
            );

            return $sessionToMonitoringServiceBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_HANDLER, function (Container $container) {
            return $this->getSessionHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface[]
     */
    protected function getSessionHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYvesSessionLockReleaserPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_YVES_SESSION_LOCK_RELEASER, function (Container $container) {
            return $this->getYvesSessionLockReleaserPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedSessionLockReleaserPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ZED_SESSION_LOCK_RELEASER, function (Container $container) {
            return $this->getZedSessionLockReleaserPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[]
     */
    protected function getYvesSessionLockReleaserPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[]
     */
    protected function getZedSessionLockReleaserPlugins(): array
    {
        return [];
    }
}
