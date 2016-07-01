<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Refund;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorBridge;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group RefundDependencyProvider
 */
class RefundDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddSalesAggregatorFacade()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::FACADE_SALES_AGGREGATOR, $container);
        $this->assertInstanceOf(RefundToSalesAggregatorBridge::class, $container[RefundDependencyProvider::FACADE_SALES_AGGREGATOR]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddRefundCalculatorPlugin()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::PLUGIN_REFUND_CALCULATOR, $container);
        $this->assertInstanceOf(RefundCalculatorPluginInterface::class, $container[RefundDependencyProvider::PLUGIN_REFUND_CALCULATOR]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddSalesQueryContainer()
    {
        $refundDependencyProvider = new RefundDependencyProvider();
        $container = new Container();
        $refundDependencyProvider->provideBusinessLayerDependencies($container);

        $this->assertArrayHasKey(RefundDependencyProvider::QUERY_CONTAINER_SALES, $container);
        $this->assertInstanceOf(SalesQueryContainerInterface::class, $container[RefundDependencyProvider::QUERY_CONTAINER_SALES]);
    }

}
