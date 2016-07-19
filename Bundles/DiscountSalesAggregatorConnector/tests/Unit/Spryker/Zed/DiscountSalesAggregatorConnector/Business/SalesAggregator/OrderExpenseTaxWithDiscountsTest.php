<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpenseTaxWithDiscounts;
use Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade\DiscountSalesAggregatorConnectorToTaxInterface;

class OrderExpenseTaxWithDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAggregateShouldAddDiscountToGrossPriceWithDiscounts()
    {
        $discountTaxBridgeMock = $this->createDiscountTaxBridgeMock();
        $discountTaxBridgeMock->expects($this->at(1))
            ->method('getAccruedTaxAmountFromGrossPrice')
            ->willReturn(10);

        $discountTaxBridgeMock->expects($this->at(2))
            ->method('getAccruedTaxAmountFromGrossPrice')
            ->willReturn(20);

        $orderExpenseWithDiscounts = $this->createOrderExpenseWithDiscounts($discountTaxBridgeMock);

        $orderTransfer = $this->createOrderTransfer();

        $orderExpenseWithDiscounts->aggregate($orderTransfer);

        $this->assertEquals(10, $orderTransfer->getExpenses()[0]->getUnitTaxAmountWithDiscounts());
        $this->assertEquals(20, $orderTransfer->getExpenses()[0]->getSumTaxAmountWithDiscounts());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
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
     * @param \Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade\DiscountSalesAggregatorConnectorToTaxInterface $discountTaxBridge
     *
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\OrderExpenseTaxWithDiscounts
     */
    protected function createOrderExpenseWithDiscounts(DiscountSalesAggregatorConnectorToTaxInterface $discountTaxBridge = null)
    {
        if ($discountTaxBridge === null) {
            $discountTaxBridge = $this->createDiscountTaxBridgeMock();
        }

        return new OrderExpenseTaxWithDiscounts($discountTaxBridge);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\\Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade\DiscountSalesAggregatorConnectorToTaxInterface
     */
    protected function createDiscountTaxBridgeMock()
    {
        return $this->getMock(DiscountSalesAggregatorConnectorToTaxInterface::class);
    }

}
