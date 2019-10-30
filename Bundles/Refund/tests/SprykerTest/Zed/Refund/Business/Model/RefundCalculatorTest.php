<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Business\Model\RefundCalculator;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Model
 * @group RefundCalculatorTest
 * Add your own group annotations below this line
 */
class RefundCalculatorTest extends Unit
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
     * @param \Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface[] $refundCalculatorPlugins
     *
     * @return \Spryker\Zed\Refund\Business\Model\RefundCalculator
     */
    protected function getRefundCalculator(array $refundCalculatorPlugins)
    {
        $refund = new RefundCalculator(
            $refundCalculatorPlugins,
            $this->getSalesFacadeMock()
        );

        return $refund;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    protected function getSalesFacadeMock()
    {
        $salesFacadeMock = $this->getMockBuilder(RefundToSalesInterface::class)->getMock();
        $salesFacadeMock->method('getOrderByIdSalesOrder')->willReturn(new OrderTransfer());

        return $salesFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface
     */
    protected function getRefundCalculationPlugin()
    {
        $refundCalculatorPluginMock = $this->getMockBuilder(RefundCalculatorPluginInterface::class)->getMock();
        $refundCalculatorPluginMock->expects($this->once())->method('calculateRefund')->willReturnArgument(0);

        return $refundCalculatorPluginMock;
    }
}
