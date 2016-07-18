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
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class ItemDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemDiscountsShouldBeHydratedFromEntities()
    {
        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $itemCalculatedDiscounts = $orderTransfer->getItems()[0]->getCalculatedDiscounts();
        $this->assertEquals(100, $itemCalculatedDiscounts[0]->getSumGrossAmount());
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
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setIdSalesExpense(1);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\ItemDiscounts
     */
    protected function createItemDiscountsAggregator()
    {
        $discountQueryContainer = $this->createDiscountQueryContainer();
        $discountQueryMock = $this->createDiscountQueryMock();

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

        $discountQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($objectCollection);

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

}
