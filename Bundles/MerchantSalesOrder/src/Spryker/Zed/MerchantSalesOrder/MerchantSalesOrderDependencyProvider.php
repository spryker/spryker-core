<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeBridge;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeBridge;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_ORDER_POST_CREATE = 'PLUGINS_MERCHANT_ORDER_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_ORDER_EXPANDER = 'PLUGINS_MERCHANT_ORDER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_ORDER_FILTER = 'PLUGINS_MERCHANT_ORDER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCalculationFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addMerchantOrderPostCreatePlugins($container);
        $container = $this->addMerchantOrderExpanderPlugins($container);
        $container = $this->addMerchantOrderFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new MerchantSalesOrderToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
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
            return new MerchantSalesOrderToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
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
            return new MerchantSalesOrderToSalesFacadeBridge(
                $container->getLocator()->sales()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOrderPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_ORDER_POST_CREATE, function () {
            return $this->getMerchantOrderPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface>
     */
    protected function getMerchantOrderPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOrderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_ORDER_EXPANDER, function () {
            return $this->getMerchantOrderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOrderFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_ORDER_FILTER, function () {
            return $this->getMerchantOrderFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface>
     */
    protected function getMerchantOrderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface>
     */
    protected function getMerchantOrderFilterPlugins(): array
    {
        return [];
    }
}
