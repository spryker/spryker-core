<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeBridge;

class SalesOrderThresholdDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES = 'SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_TAX = 'FACADE_TAX';
    public const FACADE_SALES = 'FACADE_SALES';
    public const PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY = 'PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addSalesOrderThresholdDataSourceStrategies($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addTaxFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addSalesOrderThresholdStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderThresholdDataSourceStrategies(Container $container): Container
    {
        $container[static::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES] = function () {
            return $this->getSalesOrderThresholdDataSourceStrategies();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new SalesOrderThresholdToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMoneyFacade(Container $container): Container
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new SalesOrderThresholdToMoneyFacadeBridge($container->getLocator()->money()->facade());
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
            return new SalesOrderThresholdToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new SalesOrderThresholdToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
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
            return new SalesOrderThresholdToTaxFacadeBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface[]
     */
    protected function getSalesOrderThresholdDataSourceStrategies(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderThresholdStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY] = function () {
            return $this->getSalesOrderThresholdStrategyPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new SalesOrderThresholdToSalesFacadeBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface[]
     */
    protected function getSalesOrderThresholdStrategyPlugins(): array
    {
        return [];
    }
}
