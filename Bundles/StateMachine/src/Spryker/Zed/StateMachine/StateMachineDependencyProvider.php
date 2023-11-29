<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StateMachine\Dependency\Service\StateMachineToUtilSanitizeXssServiceBridge;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 */
class StateMachineDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_STATE_MACHINE_HANDLERS = 'PLUGINS_STATE_MACHINE_HANDLERS';

    /**
     * @var string
     */
    public const PLUGIN_GRAPH = 'PLUGIN_GRAPH';

    /**
     * @var string
     */
    public const SERVICE_NETWORK = 'util network service';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE_XSS = 'SERVICE_UTIL_SANITIZE_XSS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::PLUGINS_STATE_MACHINE_HANDLERS, function () {
            return $this->getStateMachineHandlers();
        });

        $container->set(static::PLUGIN_GRAPH, function () {
            return $this->getGraphPlugin();
        });

        $container->set(static::SERVICE_NETWORK, function (Container $container) {
            return $container->getLocator()->utilNetwork()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::PLUGINS_STATE_MACHINE_HANDLERS, function () {
            return $this->getStateMachineHandlers();
        });

        $container = $this->addUtilSanitizeXssService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeXssService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE_XSS, function (Container $container) {
            return new StateMachineToUtilSanitizeXssServiceBridge(
                $container->getLocator()->utilSanitizeXss()->service(),
            );
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraphPlugin()
    {
        return new GraphPlugin();
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface>
     */
    protected function getStateMachineHandlers()
    {
        return [];
    }
}
