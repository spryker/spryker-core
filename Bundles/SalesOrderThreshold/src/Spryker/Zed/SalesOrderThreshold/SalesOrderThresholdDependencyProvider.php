<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeAdapter;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeBridge;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeBridge;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class SalesOrderThresholdDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES = 'SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @var string
     */
    public const FACADE_TAX = 'FACADE_TAX';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
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
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderThresholdDataSourceStrategies(Container $container): Container
    {
        $container->set(static::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES, function () {
            return $this->getSalesOrderThresholdDataSourceStrategies();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new SalesOrderThresholdToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new SalesOrderThresholdToMoneyFacadeBridge($container->getLocator()->money()->facade());
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
            return new SalesOrderThresholdToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new SalesOrderThresholdToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container): Container
    {
        $container->set(static::FACADE_TAX, function (Container $container) {
            return new SalesOrderThresholdToTaxFacadeBridge($container->getLocator()->tax()->facade());
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
            return new SalesOrderThresholdToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface>
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
        $container->set(static::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, function () {
            return $this->getSalesOrderThresholdStrategyPlugins();
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
            return new SalesOrderThresholdToSalesFacadeAdapter($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface>
     */
    protected function getSalesOrderThresholdStrategyPlugins(): array
    {
        return [];
    }
}
