<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Dependency\Facade\RefundToMoneyBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorBridge;
use Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group RefundDependencyProviderTest
 */
class RefundDependencyProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddSalesAggregatorFacade()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::FACADE_SALES_AGGREGATOR, $container);
        $this->assertInstanceOf(RefundToSalesAggregatorBridge::class, $container[RefundDependencyProvider::FACADE_SALES_AGGREGATOR]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddRefundableItemAmountCalculatorPlugin()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::PLUGIN_ITEM_REFUND_CALCULATOR, $container);
        $this->assertInstanceOf(RefundCalculatorPluginInterface::class, $container[RefundDependencyProvider::PLUGIN_ITEM_REFUND_CALCULATOR]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddRefundableExpenseAmountCalculatorPlugin()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::PLUGIN_ITEM_REFUND_CALCULATOR, $container);
        $this->assertInstanceOf(RefundCalculatorPluginInterface::class, $container[RefundDependencyProvider::PLUGIN_EXPENSE_REFUND_CALCULATOR]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddSalesQueryContainer()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::QUERY_CONTAINER_SALES, $container);
        $this->assertInstanceOf(SalesQueryContainerInterface::class, $container[RefundDependencyProvider::QUERY_CONTAINER_SALES]);
    }

    /**
     * @return void
     */
    public function testProvideCommunicationLayerDependenciesShouldAddRefundToMoneyBridge()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideCommunicationLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::FACADE_MONEY, $container);
        $this->assertInstanceOf(RefundToMoneyBridge::class, $container[RefundDependencyProvider::FACADE_MONEY]);
    }

    /**
     * @return void
     */
    public function testProvideCommunicationLayerDependenciesShouldAddDateFormatter()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $container = $refundDependencyProvider->provideCommunicationLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::SERVICE_DATE_TIME, $container);
        $this->assertInstanceOf(UtilDateTimeServiceInterface::class, $container[RefundDependencyProvider::SERVICE_DATE_TIME]);
    }

}
