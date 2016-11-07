<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesBridge;
use Spryker\Zed\Oms\Dependency\Facade\OmsToUtilNetworkBridge;
use Spryker\Zed\Oms\Dependency\Facade\OmsToUtilSanitizeBridge;
use Spryker\Zed\Oms\Dependency\Facade\OmsToUtilTextBridge;
use Spryker\Zed\Oms\Dependency\QueryContainer\OmsToSalesBridge AS PersistenceOmsToSalesBridge;

class OmsDependencyProvider extends AbstractBundleDependencyProvider
{

    const CONDITION_PLUGINS = 'CONDITION_PLUGINS';
    const COMMAND_PLUGINS = 'COMMAND_PLUGINS';

    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const PLUGIN_GRAPH = 'PLUGIN_GRAPH';
    const PLUGINS_RESERVATION = 'PLUGIN_RESERVATION';

    const FACADE_SALES = 'FACADE_SALES';
    const FACADE_UTIL_TEXT = 'FACADE_UTIL_TEXT';
    const FACADE_UTIL_SANITIZE = 'FACADE_UTIL_SANITIZE';
    const FACADE_UTIL_NETWORK = 'FACADE_UTIL_NETWORK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CONDITION_PLUGINS] = function (Container $container) {
            return $this->getConditionPlugins($container);
        };

        $container[self::COMMAND_PLUGINS] = function (Container $container) {
            return $this->getCommandPlugins($container);
        };

        $container[self::FACADE_SALES] = function (Container $container) {
            return new OmsToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[self::FACADE_UTIL_TEXT] = function (Container $container) {
            return new OmsToUtilTextBridge($container->getLocator()->utilText()->facade());
        };

        $container[self::FACADE_UTIL_SANITIZE] = function (Container $container) {
            return new OmsToUtilSanitizeBridge($container->getLocator()->utilSanitize()->facade());
        };

        $container[self::FACADE_UTIL_NETWORK] = function (Container $container) {
            return new OmsToUtilNetworkBridge($container->getLocator()->utilNetwork()->facade());
        };

        $container[self::PLUGIN_GRAPH] = function (Container $container) {
            return $this->getGraphPlugin();
        };

        $container[self::PLUGINS_RESERVATION] = function (Container $container) {
            return $this->getReservationHandlerPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection
     */
    protected function getConditionPlugins(Container $container)
    {
        return new ConditionCollection();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection
     */
    protected function getCommandPlugins(Container $container)
    {
        return new CommandCollection();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new PersistenceOmsToSalesBridge($container->getLocator()->sales()->queryContainer());
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container)
    {
        return [];
    }

}
