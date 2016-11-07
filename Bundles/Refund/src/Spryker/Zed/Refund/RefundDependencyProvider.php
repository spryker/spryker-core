<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund;

use Spryker\Shared\Library\Context;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Communication\Plugin\RefundableExpenseAmountCalculatorPlugin;
use Spryker\Zed\Refund\Communication\Plugin\RefundableItemAmountCalculatorPlugin;
use Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorBridge;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SALES_AGGREGATOR = 'sales aggregator facade';
    const FACADE_MONEY = 'money facade';
    const QUERY_CONTAINER_SALES = 'sales query container';
    const PLUGIN_ITEM_REFUND_CALCULATOR = 'item refund calculator plugin';
    const PLUGIN_EXPENSE_REFUND_CALCULATOR = 'expense refund calculator plugin';
    const DATE_FORMATTER = 'date formatter';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addItemRefundCalculatorPlugin($container);
        $container = $this->addExpenseRefundCalculatorPlugin($container);
        $container = $this->addSalesAggregatorFacade($container);
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
    protected function addSalesAggregatorFacade(Container $container)
    {
        $container[static::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new RefundToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
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
        $container[static::DATE_FORMATTER] = function () {
            return new DateFormatter(Context::getInstance(Context::CONTEXT_ZED));
        };

        return $container;
    }

}
