<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Plugin\Connection;

class StateMachineDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGINS_STATE_MACHINE_HANDLERS = 'PLUGINS_STATE_MACHINE_HANDLERS';
    const PLUGIN_GRAPH = 'PLUGIN_GRAPH';
    const PLUGIN_PROPEL_CONNECTION = 'PLUGIN_PROPEL_CONNECTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGINS_STATE_MACHINE_HANDLERS] = function (Container $container) {
            return $this->getStateMachineHandlers();
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        $container[self::PLUGIN_GRAPH] = function (Container $container) {
            return $this->getGraphPlugin();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS_STATE_MACHINE_HANDLERS] = function (Container $container) {
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
