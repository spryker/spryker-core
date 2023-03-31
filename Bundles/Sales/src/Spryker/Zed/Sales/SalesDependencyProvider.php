<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeBridge;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const SERVICE_DATE_FORMATTER = 'SERVICE_DATE_FORMATTER';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_LOCALE = 'PROPEL_QUERY_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const ORDER_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_EXPANDER_PRE_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const HYDRATE_ORDER_PLUGINS = 'HYDRATE_ORDER_PLUGINS';

    /**
     * @var string
     */
    public const ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const ITEM_TRANSFORMER_STRATEGY_PLUGINS = 'ITEM_TRANSFORMER_STRATEGY_PLUGINS';

    /**
     * @var string
     */
    public const UI_SALES_TABLE_PLUGINS = 'UI_SALES_TABLE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_POST_SAVE = 'PLUGINS_ORDER_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_ITEM_PRE_TRANSFORMER = 'PLUGINS_ITEM_PRE_TRANSFORMER';

    /**
     * @var string
     */
    public const PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER = 'PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEM_EXPANDER = 'PLUGINS_ORDER_ITEM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_ORDER_EXPANDER = 'PLUGINS_SEARCH_ORDER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_SEARCH_QUERY_EXPANDER = 'PLUGINS_ORDER_SEARCH_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK = 'PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_TABLE_EXPANDER = 'PLUGINS_ORDER_ITEMS_TABLE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_ITEMS_POST_SAVE = 'PLUGINS_ORDER_ITEMS_POST_SAVE';

    /**
     * @deprecated Will be removed in the next major version.
     *
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addLocalePropelQuery($container);
        $container = $this->addOrderExpanderPreSavePlugins($container);
        $container = $this->addHydrateOrderPlugins($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addOrderItemExpanderPreSavePlugins($container);
        $container = $this->addItemTransformerStrategyPlugins($container);
        $container = $this->addOrderPostSavePlugins($container);
        $container = $this->addItemPreTransformerPlugins($container);
        $container = $this->addUniqueOrderItemsExpanderPlugins($container);
        $container = $this->addOrderItemExpanderPlugins($container);
        $container = $this->addSearchOrderExpanderPlugins($container);
        $container = $this->addOrderSearchQueryExpanderPlugins($container);
        $container = $this->addCustomerOrderAccessCheckPlugins($container);
        $container = $this->addOrderItemsPostSavePlugins($container);

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
        $container = $this->addOmsFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addDateTimeFormatter($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMoneyPlugin($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addSalesTablePlugins($container);
        $container = $this->addOrderItemsTableExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderExpanderPreSavePlugins(Container $container)
    {
        $container->set(static::ORDER_EXPANDER_PRE_SAVE_PLUGINS, function (Container $container) {
            return $this->getOrderExpanderPreSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addHydrateOrderPlugins(Container $container)
    {
        $container->set(static::HYDRATE_ORDER_PLUGINS, function (Container $container) {
            return $this->getOrderHydrationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemExpanderPreSavePlugins(Container $container)
    {
        $container->set(static::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS, function (Container $container) {
            return $this->getOrderItemExpanderPreSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemTransformerStrategyPlugins(Container $container): Container
    {
        $container->set(static::ITEM_TRANSFORMER_STRATEGY_PLUGINS, function (Container $container) {
            return $this->getItemTransformerStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesTablePlugins(Container $container)
    {
        $container->set(static::UI_SALES_TABLE_PLUGINS, function (Container $container) {
            return $this->getSalesTablePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new SalesToMoneyBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container)
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container)
    {
        $container->set(static::FACADE_SEQUENCE_NUMBER, function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container)
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new SalesToUserBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new SalesToLocaleBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new SalesToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new SalesToCustomerBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeFormatter(Container $container)
    {
        $container->set(static::SERVICE_DATE_FORMATTER, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocalePropelQuery(Container $container)
    {
        $container->set(static::PROPEL_QUERY_LOCALE, $container->factory(function (Container $container) {
            return SpyLocaleQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new SalesToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new SalesToCalculationBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_POST_SAVE, function () {
            return $this->getOrderPostSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemPreTransformerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ITEM_PRE_TRANSFORMER, function () {
            return $this->getItemPreTransformerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUniqueOrderItemsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER, function () {
            return $this->getUniqueOrderItemsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEM_EXPANDER, function () {
            return $this->getOrderItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchOrderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_ORDER_EXPANDER, function () {
            return $this->getSearchOrderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_SEARCH_QUERY_EXPANDER, function () {
            return $this->getOrderSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerOrderAccessCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK, function () {
            return $this->getCustomerOrderAccessCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemsTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_TABLE_EXPANDER, function () {
            return $this->getOrderItemsTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemsPostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_ITEMS_POST_SAVE, function () {
            return $this->getOrderItemsPostSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface>
     */
    protected function getOrderExpanderPreSavePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface>
     */
    protected function getOrderHydrationPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface>
     */
    protected function getOrderItemExpanderPreSavePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\ItemTransformerStrategyPluginInterface>
     */
    public function getItemTransformerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface>
     */
    protected function getSalesTablePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>
     */
    protected function getOrderPostSavePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\ItemPreTransformerPluginInterface>
     */
    protected function getItemPreTransformerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface>
     */
    protected function getUniqueOrderItemsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface>
     */
    protected function getOrderItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface>
     */
    protected function getSearchOrderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface>
     */
    protected function getOrderSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface>
     */
    protected function getCustomerOrderAccessCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsTableExpanderPluginInterface>
     */
    protected function getOrderItemsTableExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface>
     */
    protected function getOrderItemsPostSavePlugins(): array
    {
        return [];
    }
}
