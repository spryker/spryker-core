<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use PHPUnit_Framework_TestCase;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountSalesAggregatorConnector
 * @group Business
 * @group SalesAggregator
 * @group ItemDiscountsTest
 */
class ItemDiscountsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemDiscountsShouldBeHydratedFromEntities()
    {
        $discountCollection = $this->createDiscountCollection();
        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $itemCalculatedDiscounts = $orderTransfer->getItems()[0]->getCalculatedDiscounts();
        $this->assertSame(100, $itemCalculatedDiscounts[0]->getSumGrossAmount());
    }

    /**
     * @return void
     */
    public function testItemDiscountsWhenDiscountAmountIsBiggerThanItemAmountShouldNotApplyBiggerThatItemAmount()
    {
        $discountCollection = $this->createDiscountCollection();
        $discountCollection->get(0)->setAmount(1000);

        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $itemTransfer = $orderTransfer->getItems()[0];
        $this->assertSame(0, $itemTransfer->getUnitGrossPriceWithDiscounts());
        $this->assertSame(0, $itemTransfer->getSumGrossPriceWithDiscounts());
    }

    /**
     * @return void
     */
    public function testAggregateShouldSubtractCalculatedDiscountAmountFromItemRefundableAmount()
    {
        $discountCollection = $this->createDiscountCollection();
        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $itemCalculatedRefundableAmount = $orderTransfer->getItems()[0]->getRefundableAmount();
        $this->assertEquals(900, $itemCalculatedRefundableAmount);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setIdSalesOrderItem(1);
        $itemTransfer->setRefundableAmount(1000);

        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setIdSalesExpense(1);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $discountCollection
     *
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts
     */
    protected function createItemDiscountsAggregator(ObjectCollection $discountCollection)
    {
        $discountQueryContainer = $this->createDiscountQueryContainer();
        $discountQueryMock = $this->createDiscountQueryMock();

        $discountQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($discountCollection);

        $discountQueryMock->expects($this->once())
            ->method('filterByFkSalesOrderItem')
            ->with($this->isType('array'))
            ->willReturn($discountQueryMock);

        $discountQueryContainer->expects($this->once())
            ->method('querySalesDiscount')
            ->willReturn($discountQueryMock);

        return new ItemDiscounts($discountQueryContainer);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpySalesDiscountQuery::class)
            ->setMethods(['filterByFkSalesOrderItem', 'find'])
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainer()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function createDiscountCollection()
    {
        $objectCollection = new ObjectCollection();

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setDisplayName('test');
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(100);
        $objectCollection->append($salesDiscountEntity);

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setDisplayName('test');
        $salesDiscountEntity->setFkSalesExpense(1);
        $salesDiscountEntity->setAmount(200);
        $objectCollection->append($salesDiscountEntity);

        return $objectCollection;
    }

}
