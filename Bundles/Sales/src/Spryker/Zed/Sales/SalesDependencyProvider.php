<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeBridge;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';
    public const FACADE_OMS = 'FACADE_OMS';
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    public const FACADE_USER = 'FACADE_USER';
    public const SERVICE_DATE_FORMATTER = 'SERVICE_DATE_FORMATTER';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const STORE = 'STORE';

    public const ORDER_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_EXPANDER_PRE_SAVE_PLUGINS';
    public const HYDRATE_ORDER_PLUGINS = 'HYDRATE_ORDER_PLUGINS';
    public const ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS';
    public const ITEM_TRANSFORMER_STRATEGY_PLUGINS = 'ITEM_TRANSFORMER_STRATEGY_PLUGINS';
    public const UI_SALES_TABLE_PLUGINS = 'UI_SALES_TABLE_PLUGINS';
    public const PLUGINS_ORDER_POST_SAVE = 'PLUGINS_ORDER_POST_SAVE';
    public const PLUGINS_ITEM_PRE_TRANSFORMER = 'PLUGINS_ITEM_PRE_TRANSFORMER';
    public const PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER = 'PLUGINS_UNIQUE_ORDER_ITEMS_EXPANDER';
    public const PLUGINS_ORDER_ITEM_EXPANDER = 'PLUGINS_ORDER_ITEM_EXPANDER';
    public const PLUGINS_SEARCH_ORDER_EXPANDER = 'PLUGINS_SEARCH_ORDER_EXPANDER';
    public const PLUGINS_ORDER_SEARCH_QUERY_EXPANDER = 'PLUGINS_ORDER_SEARCH_QUERY_EXPANDER';
    public const PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK = 'PLUGINS_CUSTOMER_ORDER_ACCESS_CHECK';

    public const PLUGINS_ORDER_ITEMS_TABLE_EXPANDER = 'PLUGINS_ORDER_ITEMS_TABLE_EXPANDER';

    /**
     * @deprecated Will be removed in the next major version.
     */
    public const FACADE_LOCALE = 'LOCALE_FACADE';
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addStore($container);
        $container = $this->addLocaleQueryContainer($container);
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
        $container = $this->addQuoteClient($container);

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
    protected function addStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->queryContainer();
        });

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
    protected function addQuoteClient(Container $container)
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new SalesToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface[]
     */
    protected function getOrderExpanderPreSavePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[]
     */
    protected function getOrderHydrationPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface[]
     */
    protected function getOrderItemExpanderPreSavePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\ItemTransformerStrategyPluginInterface[]
     */
    public function getItemTransformerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface[]
     */
    protected function getSalesTablePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface[]
     */
    protected function getOrderPostSavePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\ItemPreTransformerPluginInterface[]
     */
    protected function getItemPreTransformerPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface[]
     */
    protected function getUniqueOrderItemsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[]
     */
    protected function getOrderItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[]
     */
    protected function getSearchOrderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface[]
     */
    protected function getOrderSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface[]
     */
    protected function getCustomerOrderAccessCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsTableExpanderPluginInterface[]
     */
    protected function getOrderItemsTableExpanderPlugins(): array
    {
        return [];
    }
}
