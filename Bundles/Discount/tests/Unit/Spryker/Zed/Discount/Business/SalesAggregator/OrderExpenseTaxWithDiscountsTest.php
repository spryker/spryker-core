<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\SalesAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Business\SalesAggregator\OrderExpenseTaxWithDiscounts;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface;

class OrderExpenseTaxWithDiscountsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testAggregateShouldAddDiscountToGrossPriceWithDiscounts()
    {
        $discountTaxBridgeMock = $this->createDiscountTaxBridgeMock();
        $discountTaxBridgeMock->expects($this->at(0))
            ->method('getTaxAmountFromGrossPrice')
            ->willReturn(10);

        $discountTaxBridgeMock->expects($this->at(1))
            ->method('getTaxAmountFromGrossPrice')
            ->willReturn(20);

        $orderExpenseWithDiscounts = $this->createOrderExpenseWithDiscounts($discountTaxBridgeMock);

        $orderTransfer = $this->createOrderTransfer();

        $orderExpenseWithDiscounts->aggregate($orderTransfer);

        $this->assertEquals(10, $orderTransfer->getExpenses()[0]->getUnitTaxAmountWithDiscounts());
        $this->assertEquals(20, $orderTransfer->getExpenses()[0]->getSumTaxAmountWithDiscounts());
    }

    /**
     * @return OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setUnitGrossPriceWithDiscounts(100);
        $expenseTransfer->setTaxRate(10);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToTaxBridgeInterface $discountTaxBridge
     *
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\OrderExpenseTaxWithDiscounts
     */
    protected function createOrderExpenseWithDiscounts(DiscountToTaxBridgeInterface $discountTaxBridge = null)
    {
        if ($discountTaxBridge === null) {
            $discountTaxBridge = $this->createDiscountTaxBridgeMock();
        }

        return new OrderExpenseTaxWithDiscounts($discountTaxBridge);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DiscountToTaxBridgeInterface
     */
    protected function createDiscountTaxBridgeMock()
    {
        return $this->getMock(DiscountToTaxBridgeInterface::class);
    }
}
