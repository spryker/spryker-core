<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeBridge;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeBridge;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeBridge;
use Spryker\Zed\MerchantOms\Dependency\Service\MerchantOmsToUtilDataReaderServiceBridge;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class MerchantOmsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const FACADE_STATE_MACHINE = 'FACADE_STATE_MACHINE';
    public const FACADE_MERCHANT_SALES_ORDER = 'FACADE_MERCHANT_SALES_ORDER';

    public const PLUGINS_STATE_MACHINE_CONDITION = 'PLUGINS_STATE_MACHINE_CONDITION';
    public const PLUGINS_PLUGINS_STATE_MACHINE_COMMAND = 'PLUGINS_PLUGINS_STATE_MACHINE_COMMAND';

    public const PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM = 'PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM';
    public const PROPEL_QUERY_STATE_MACHINE_ITEM_STATE_HISTORY = 'PROPEL_QUERY_STATE_MACHINE_ITEM_STATE_HISTORY';

    public const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantFacade($container);
        $container = $this->addStateMachineFacade($container);
        $container = $this->addMerchantSalesOrderFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addStateMachineCommandPlugins($container);
        $container = $this->addStateMachineConditionPlugins($container);
        $container = $this->addMerchantSalesOrderFacade($container);
        $container = $this->addUtilDataReaderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addMerchantSalesOrderItemPropelQuery($container);
        $container = $this->addStateMachineItemStateHistoryPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderItemPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM, $container->factory(function () {
            return SpyMerchantSalesOrderItemQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineItemStateHistoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STATE_MACHINE_ITEM_STATE_HISTORY, $container->factory(function () {
            return SpyStateMachineItemStateHistoryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineFacade(Container $container): Container
    {
        $container->set(static::FACADE_STATE_MACHINE, function (Container $container) {
            return new MerchantOmsToStateMachineFacadeBridge($container->getLocator()->stateMachine()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantOmsToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_SALES_ORDER, function (Container $container) {
            return new MerchantOmsToMerchantSalesOrderFacadeBridge($container->getLocator()->merchantSalesOrder()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDataReaderService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATA_READER, function (Container $container) {
            return new MerchantOmsToUtilDataReaderServiceBridge($container->getLocator()->utilDataReader()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineConditionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STATE_MACHINE_CONDITION, function () {
            return $this->getStateMachineConditionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineCommandPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PLUGINS_STATE_MACHINE_COMMAND, function () {
            return $this->getStateMachineCommandPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface[]
     */
    protected function getStateMachineConditionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    protected function getStateMachineCommandPlugins(): array
    {
        return [];
    }
}
