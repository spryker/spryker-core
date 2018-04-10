<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeBridge;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_COUNTRY = 'FACADE_COUNTRY';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    const FACADE_USER = 'FACADE_USER';
    const SERVICE_DATE_FORMATTER = 'date formatter service';
    const FACADE_MONEY = 'money facade';
    const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    const QUERY_CONTAINER_LOCALE = 'locale query container';
    const SERVICE_UTIL_SANITIZE = 'util sanitize service';
    const STORE = 'store';

    const HYDRATE_ORDER_PLUGINS = 'hydrate order plugins';
    const ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS = 'ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS';
    //todo:
    const HYDRATE_ORDER_PLUGIN = 'plugins hydrate order';
    const PLUGINS_ORDER_PRE_SAVE = 'PLUGINS_ORDER_PRE_SAVE';
    const UI_SALES_TABLE_PLUGINS = 'UI sales table plugins';

    /**
     * @deprecated Will be removed in the next major version.
     */
    const FACADE_LOCALE = 'LOCALE_FACADE';
    const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addStore($container);
        $container = $this->addLocaleQueryContainer($container);
        $container = $this->addHydrateOrderPlugins($container);
        $container = $this->addOrderPreSavePlugins($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addOrderItemExpanderPreSavePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addOmsFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addDateTimeFormatter($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMoneyPlugin($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addSalesTablePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addHydrateOrderPlugins(Container $container)
    {
        $container[static::HYDRATE_ORDER_PLUGIN] = function (Container $container) {
            return $this->getOrderHydrationPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderItemExpanderPreSavePlugins(Container $container)
    {
        $container[static::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS] = function (Container $container) {
            return $this->getOrderItemExpanderPreSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesTablePlugins(Container $container)
    {
        $container[static::UI_SALES_TABLE_PLUGINS] = function (Container $container) {
            return $this->getSalesTablePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderPreSavePlugins(Container $container)
    {
        $container[static::PLUGINS_ORDER_PRE_SAVE] = function () {
            return $this->getOrderPreSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new SalesToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container[static::FACADE_OMS] = function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container)
    {
        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container)
    {
        $container[static::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container)
    {
        $container[static::FACADE_USER] = function (Container $container) {
            return new SalesToUserBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new SalesToCustomerBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeFormatter(Container $container)
    {
        $container[static::SERVICE_DATE_FORMATTER] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new SalesToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container[static::FACADE_CALCULATION] = function (Container $container) {
            return new SalesToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface[]
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
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPreSavePluginInterface[]
     */
    protected function getOrderPreSavePlugins(): array
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
}
