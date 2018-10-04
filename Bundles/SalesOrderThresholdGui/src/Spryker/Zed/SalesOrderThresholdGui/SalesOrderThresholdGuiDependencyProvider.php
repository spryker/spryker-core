<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeBridge;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeBridge;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToMoneyFacadeBridge;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeBridge;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToStoreFacadeBridge;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeBridge;

class SalesOrderThresholdGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_SALES_ORDER_THRESHOLD = 'FACADE_SALES_ORDER_THRESHOLD';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_TAX = 'FACADE_TAX';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addSalesOrderThresholdFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addTaxFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new SalesOrderThresholdGuiToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new SalesOrderThresholdGuiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderThresholdFacade(Container $container): Container
    {
        $container[static::FACADE_SALES_ORDER_THRESHOLD] = function (Container $container) {
            return new SalesOrderThresholdGuiToSalesOrderThresholdFacadeBridge($container->getLocator()->salesOrderThreshold()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new SalesOrderThresholdGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new SalesOrderThresholdGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container): Container
    {
        $container[static::FACADE_TAX] = function (Container $container) {
            return new SalesOrderThresholdGuiToTaxFacadeBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }
}
