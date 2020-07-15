<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Facade\OmsToMailBridge;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesBridge;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeBridge;
use Spryker\Zed\Oms\Dependency\QueryContainer\OmsToSalesBridge as PersistenceOmsToSalesBridge;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilNetworkBridge;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeBridge;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextBridge;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 */
class OmsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CONDITION_PLUGINS = 'CONDITION_PLUGINS';
    public const COMMAND_PLUGINS = 'COMMAND_PLUGINS';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    public const PLUGIN_GRAPH = 'PLUGIN_GRAPH';
    public const PLUGINS_RESERVATION = 'PLUGIN_RESERVATION';
    public const PLUGINS_RESERVATION_AGGREGATION = 'PLUGINS_RESERVATION_AGGREGATION';
    public const PLUGINS_OMS_RESERVATION_AGGREGATION = 'PLUGINS_OMS_RESERVATION_AGGREGATION';
    public const PLUGINS_RESERVATION_EXPORT = 'PLUGINS_RESERVATION_EXPORT';
    public const PLUGINS_OMS_ORDER_MAIL_EXPANDER = 'PLUGINS_OMS_ORDER_MAIL_EXPANDER';
    public const PLUGINS_OMS_MANUAL_EVENT_GROUPER = 'PLUGINS_OMS_MANUAL_EVENT_GROUPER';
    public const PLUGINS_OMS_RESERVATION_READER_STRATEGY = 'PLUGINS_OMS_RESERVATION_READER_STRATEGY';
    public const PLUGINS_OMS_RESERVATION_WRITER_STRATEGY = 'PLUGINS_OMS_RESERVATION_WRITER_STRATEGY';
    public const PLUGINS_RESERVATION_HANDLER_TERMINATION_AWARE_STRATEGY = 'PLUGINS_RESERVATION_HANDLER_TERMINATION_AWARE_STRATEGY';
    public const PLUGINS_TIMEOUT_PROCESSOR = 'PLUGINS_TIMEOUT_PROCESSOR';

    public const FACADE_MAIL = 'FACADE_MAIL';
    public const FACADE_SALES = 'FACADE_SALES';

    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_UTIL_TEXT = 'FACADE_UTIL_TEXT';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const SERVICE_UTIL_NETWORK = 'SERVICE_UTIL_NETWORK';

    /**
     * @deprecated Use {@link \Spryker\Zed\Oms\OmsDependencyProvider::QUERY_CONTAINER_SALES} instead.
     */
    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addConditionPlugins($container);
        $container = $this->addCommandPlugins($container);
        $container = $this->addMailFacade($container);
        $container = $this->addUtilTextFacade($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addUtilNetworkService($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addGraphPlugin($container);
        $container = $this->addReservationHandlerPlugins($container);
        $container = $this->addReservationAggregationStrategyPlugins($container);
        $container = $this->addOmsReservationReaderStrategyPlugins($container);
        $container = $this->addOmsReservationAggregationPlugins($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addReservationExportPlugins($container);
        $container = $this->addOmsOrderMailExpanderPlugins($container);
        $container = $this->addOmsManualEventGrouperPlugins($container);
        $container = $this->addOmsReservationWriterStrategyPlugins($container);
        $container = $this->addReservationPostSaveTerminationAwareStrategyPlugins($container);
        $container = $this->addTimeoutProcessorPlugins($container);

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
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addSalesQueryContainer($container);
        $container = $this->addSalesOrderItemPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_SALES, function (Container $container) {
            return new PersistenceOmsToSalesBridge(
                $container->getLocator()->sales()->queryContainer()
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
     * @deprecated Use {@link \Spryker\Zed\Oms\OmsDependencyProvider::getReservationPostSaveTerminationAwareStrategyPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Use {@link getOmsReservationAggregationPlugins()} instead.
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[]
     */
    protected function getReservationAggregationStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface[]
     */
    protected function getOmsReservationAggregationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReservationExportPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESERVATION_EXPORT, function () {
            return $this->getReservationExportPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationExportPluginInterface[]
     */
    protected function getReservationExportPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new OmsToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConditionPlugins(Container $container): Container
    {
        $container->set(static::CONDITION_PLUGINS, function (Container $container) {
            return $this->getConditionPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommandPlugins(Container $container): Container
    {
        $container->set(static::COMMAND_PLUGINS, function (Container $container) {
            return $this->getCommandPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new OmsToMailBridge($container->getLocator()->mail()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextFacade(Container $container): Container
    {
        $container->set(static::FACADE_UTIL_TEXT, function (Container $container) {
            return new OmsToUtilTextBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new OmsToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NETWORK, function (Container $container) {
            return new OmsToUtilNetworkBridge($container->getLocator()->utilNetwork()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new OmsToSalesBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Oms\OmsDependencyProvider::addSalesQueryContainer()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_ITEM, $container->factory(function () {
            return SpySalesOrderItemQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGraphPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_GRAPH, function () {
            return $this->getGraphPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReservationHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESERVATION, function (Container $container) {
            return $this->getReservationHandlerPlugins($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link getOmsReservationAggregationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReservationAggregationStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESERVATION_AGGREGATION, function () {
            return $this->getReservationAggregationStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsReservationAggregationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OMS_RESERVATION_AGGREGATION, function () {
            return $this->getOmsReservationAggregationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[]
     */
    protected function getOmsOrderMailExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsOrderMailExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OMS_ORDER_MAIL_EXPANDER, function (Container $container) {
            return $this->getOmsOrderMailExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[]
     */
    protected function getOmsManualEventGrouperPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsManualEventGrouperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OMS_MANUAL_EVENT_GROUPER, function (Container $container) {
            return $this->getOmsManualEventGrouperPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsReservationReaderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OMS_RESERVATION_READER_STRATEGY, function (Container $container) {
            return $this->getOmsReservationReaderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[]
     */
    protected function getOmsReservationReaderStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsReservationWriterStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OMS_RESERVATION_WRITER_STRATEGY, function () {
            return $this->getOmsReservationWriterStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface[]
     */
    protected function getOmsReservationWriterStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReservationPostSaveTerminationAwareStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESERVATION_HANDLER_TERMINATION_AWARE_STRATEGY, function () {
            return $this->getReservationPostSaveTerminationAwareStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationPostSaveTerminationAwareStrategyPluginInterface[]
     */
    protected function getReservationPostSaveTerminationAwareStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTimeoutProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_TIMEOUT_PROCESSOR, function () {
            return $this->getTimeoutProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface[]
     */
    protected function getTimeoutProcessorPlugins(): array
    {
        return [];
    }
}
