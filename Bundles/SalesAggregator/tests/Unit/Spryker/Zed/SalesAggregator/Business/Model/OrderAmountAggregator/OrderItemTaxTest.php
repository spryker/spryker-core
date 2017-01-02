<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group SalesAggregator
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group OrderItemTaxTest
 */
class OrderItemTaxTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToUnitTaxForItem()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(10 / 19), $orderTransfer->getItems()[0]->getUnitTaxAmount());
    }

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToSumTaxForItem()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(20 / 19), $orderTransfer->getItems()[0]->getSumTaxAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $itemTransfer->setUnitGrossPrice(10);
        $itemTransfer->setSumGrossPrice(20);
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setTaxRate(19);
        $expenseTransfer->setSumTaxAmount(10);
        $expenseTransfer->setSumGrossPrice(20);
        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemTax
     */
    protected function createItemTaxAggregator()
    {
        $taxFacadeMock = $this->createTaxFacadeMock();

        $taxFacadeMock->expects($this->exactly(2))->method('getAccruedTaxAmountFromGrossPrice')->willReturnCallback(
            function ($grossPrice, $taxRate) {
                return round($grossPrice / $taxRate); //not testing calculation. just make sure it was applied
            }
        );

        return new ItemTax($taxFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected function createTaxFacadeMock()
    {
        return $this->getMockBuilder(SalesAggregatorToTaxInterface::class)->disableOriginalConstructor()->getMock();
    }

}
