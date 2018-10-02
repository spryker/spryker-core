<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Communication\Plugin\RefundableExpenseAmountCalculatorPlugin;
use Spryker\Zed\Refund\Communication\Plugin\RefundableItemAmountCalculatorPlugin;
use Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesBridge;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MONEY = 'money facade';
    public const FACADE_SALES = 'sales facade';
    public const FACADE_CALCULATION = 'calculation facade';

    public const QUERY_CONTAINER_SALES = 'sales query container';
    public const PLUGIN_ITEM_REFUND_CALCULATOR = 'item refund calculator plugin';
    public const PLUGIN_EXPENSE_REFUND_CALCULATOR = 'expense refund calculator plugin';
    public const SERVICE_DATE_TIME = 'date formatter';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addItemRefundCalculatorPlugin($container);
        $container = $this->addExpenseRefundCalculatorPlugin($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addSalesQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addMoneyFacade($container);
        $container = $this->addDateFormatter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container)
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new RefundToSalesBridge($container->getLocator()->sales()->facade());
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
            return new RefundToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemRefundCalculatorPlugin(Container $container)
    {
        $container[static::PLUGIN_ITEM_REFUND_CALCULATOR] = function () {
            return new RefundableItemAmountCalculatorPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpenseRefundCalculatorPlugin(Container $container)
    {
        $container[static::PLUGIN_EXPENSE_REFUND_CALCULATOR] = function () {
            return new RefundableExpenseAmountCalculatorPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new RefundToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateFormatter(Container $container)
    {
        $container[static::SERVICE_DATE_TIME] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

        return $container;
    }
}
