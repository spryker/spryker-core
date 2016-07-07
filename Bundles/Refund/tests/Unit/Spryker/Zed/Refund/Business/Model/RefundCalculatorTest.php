<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Business\Model\RefundCalculator;
use Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group RefundCalculator
 */
class RefundCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateRefundShouldReturnRefundTransfer()
    {
        $refund = $this->getRefundCalculator([]);
        $result = $refund->calculateRefund([], new SpySalesOrder());

        $this->assertInstanceOf(RefundTransfer::class, $result);
    }

    /**
     * @return void
     */
    public function testCalculateRefundShouldCallRefundPlugins()
    {
        $refundCalculationPlugin = $this->getRefundCalculationPlugin();
        $refund = $this->getRefundCalculator([$refundCalculationPlugin]);
        $result = $refund->calculateRefund([], new SpySalesOrder());

        $this->assertInstanceOf(RefundTransfer::class, $result);
    }

    /**
     * @param \Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface[] $refundCalculatorPlugins
     *
     * @return \Spryker\Zed\Refund\Business\Model\RefundCalculator
     */
    protected function getRefundCalculator(array $refundCalculatorPlugins)
    {
        $refund = new RefundCalculator(
            $this->getSalesAggregatorMock(),
            $refundCalculatorPlugins
        );

        return $refund;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface
     */
    protected function getSalesAggregatorMock()
    {
        $salesAggregatorFacadeMock = $this->getMock(RefundToSalesAggregatorInterface::class);
        $salesAggregatorFacadeMock->method('getOrderTotalsByIdSalesOrder')->willReturn(new OrderTransfer());

        return $salesAggregatorFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Refund\Communication\Plugin\RefundCalculatorPluginInterface
     */
    protected function getRefundCalculationPlugin()
    {
        $refundCalculatorPluginMock = $this->getMock(RefundCalculatorPluginInterface::class);
        $refundCalculatorPluginMock->expects($this->once())->method('calculateRefund')->willReturnArgument(0);

        return $refundCalculatorPluginMock;
    }

}
