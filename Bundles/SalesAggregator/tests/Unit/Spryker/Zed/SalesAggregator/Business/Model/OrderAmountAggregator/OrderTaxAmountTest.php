<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface;

class OrderTaxAmountTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testTaxShouldBeCalculatedFromGrandTotalUsingEffectiveTaxRate()
    {
        $orderTaxAmountAggregator = $this->createOrderTaxAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountAggregator->aggregate($orderTransfer);

        $this->assertEquals(26, $orderTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return void
     */
    public function testTaxEffectiveTaxRateShouldBeSet()
    {
        $orderTaxAmountAggregator = $this->createOrderTaxAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountAggregator->aggregate($orderTransfer);

        $this->assertEquals(19, $orderTransfer->getTotals()->getTaxTotal()->getTaxRate());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    protected function createOrderTaxAmountAggregator()
    {
        $taxFacadeMock = $this->createTaxFacadeMock();

        $taxFacadeMock->expects($this->exactly(1))->method('getTaxAmountFromGrossPrice')->willReturnCallback(
            function ($grosPrice, $taxRate) {
                return round($grosPrice / $taxRate); //not testing calculation. just make sure it was applied.
            }
        );

        return new OrderTaxAmount($taxFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected function createTaxFacadeMock()
    {
        return $this->getMockBuilder(SalesAggregatorToTaxInterface::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(500);
        $orderTransfer->setTotals($totalTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setTaxRate(19);
        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

}
