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
    public const PLUGINS_HANDLER_SESSION = 'PLUGINS_HANDLER_SESSION';

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
        $container[static::PLUGINS_HANDLER_SESSION] = function (Container $container) {
            return $this->getSessionHandlerPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerPluginInterface[]
     */
    protected function getSessionHandlerPlugins(): array
    {
        return [];
    }
}
