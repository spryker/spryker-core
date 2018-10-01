<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class StateMachineDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_STATE_MACHINE_HANDLERS = 'PLUGINS_STATE_MACHINE_HANDLERS';
    public const PLUGIN_GRAPH = 'PLUGIN_GRAPH';
    public const SERVICE_NETWORK = 'util network service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGINS_STATE_MACHINE_HANDLERS] = function () {
            return $this->getStateMachineHandlers();
        };

        $container[self::PLUGIN_GRAPH] = function () {
            return $this->getGraphPlugin();
        };

        $container[static::SERVICE_NETWORK] = function (Container $container) {
            return $container->getLocator()->utilNetwork()->service();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS_STATE_MACHINE_HANDLERS] = function () {
            return $this->getStateMachineHandlers();
        };
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraphPlugin()
    {
        return new GraphPlugin();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[]
     */
    protected function getStateMachineHandlers()
    {
        return [];
    }
}
