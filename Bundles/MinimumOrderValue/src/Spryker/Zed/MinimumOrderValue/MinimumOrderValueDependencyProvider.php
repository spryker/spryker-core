<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\HardThresholdStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithFixedFeeStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithFlexibleFeeStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithMessageStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeBridge;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeBridge;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeBridge;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeBridge;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeBridge;
use Spryker\Zed\MinimumOrderValue\Dependency\QueryContainer\MinimumOrderValueToSalesQueryContainerBridge;

class MinimumOrderValueDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES = 'MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_TAX = 'FACADE_TAX';
    public const PLUGINS_MINIMUM_ORDER_VALUE_STRATEGY = 'PLUGINS_MINIMUM_ORDER_VALUE_STRATEGY';
    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMinimumOrderValueDataSourceStrategies($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addSalesQueryContainer($container);
        $container = $this->addTaxFacade($container);
        $container = $this->addMinimumOrderValueStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMinimumOrderValueDataSourceStrategies(Container $container): Container
    {
        $container[static::MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES] = function () {
            return $this->getMinimumOrderValueDataSourceStrategies();
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
            return new MinimumOrderValueToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
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
            return new MinimumOrderValueToMoneyFacadeBridge($container->getLocator()->money()->facade());
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
            return new MinimumOrderValueToStoreFacadeBridge($container->getLocator()->store()->facade());
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
            return new MinimumOrderValueToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new MinimumOrderValueToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
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
            return new MinimumOrderValueToTaxFacadeBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected function getMinimumOrderValueDataSourceStrategies(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMinimumOrderValueStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_MINIMUM_ORDER_VALUE_STRATEGY] = function () {
            return $this->getMinimumOrderValueStrategyPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[]
     */
    protected function getMinimumOrderValueStrategyPlugins(): array
    {
        return [
            new HardThresholdStrategyPlugin(),
            new SoftThresholdWithMessageStrategyPlugin(),
            new SoftThresholdWithFixedFeeStrategyPlugin(),
            new SoftThresholdWithFlexibleFeeStrategyPlugin(),
        ];
    }
}
