<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\ProductOptionDiscounts;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductOptionDiscountConnector
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group ProductOptionDiscountsTest
 */
class ProductOptionDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProductOptionDiscountWhenOrderHaveShouldHydrateToCalculatedDiscounts()
    {
        $discountCollection = $this->createDiscountCollection();
        $productOptionAggregator = $this->createProductOptionsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();
        $productOptionAggregator->aggregate($orderTransfer);

        $productOptionTransfer = $orderTransfer->getItems()[0]->getProductOptions()[0];
        $this->assertEquals(50, $productOptionTransfer->getCalculatedDiscounts()[0]->getSumGrossAmount());
    }

    /**
     * @return void
     */
    public function testWhenDiscountUsedThenItemShouldHaveDiscountsApplied()
    {
        $discountCollection = $this->createDiscountCollection();
        $productOptionAggregator = $this->createProductOptionsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();
        $productOptionAggregator->aggregate($orderTransfer);

        $this->assertSame(700, $orderTransfer->getItems()[0]->getSumGrossPriceWithProductOptionAndDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testRefundableAmountShouldBeAfterDiscounts()
    {
        $discountCollection = $this->createDiscountCollection();
        $productOptionAggregator = $this->createProductOptionsAggregator($discountCollection);
        $orderTransfer = $this->createOrderTransfer();
        $productOptionAggregator->aggregate($orderTransfer);

        $refundableAmount = $orderTransfer->getItems()[0]->getRefundableAmount();

        $this->assertSame(700, $refundableAmount);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $discountCollection
     *
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\ProductOptionDiscounts
     */
    protected function createProductOptionsAggregator(ObjectCollection $discountCollection)
    {
        $discountQueryContainerMock = $this->createDiscountQueryContainer();

        $salesDiscountQueryMock = $this->createDiscountQueryMock();

        $salesDiscountQueryMock->expects($this->once())
            ->method('filterByFkSalesOrderItem')
            ->willReturnSelf();

        $salesDiscountQueryMock
            ->expects($this->once())
            ->method('where')
            ->willReturnSelf();

        $salesDiscountQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($discountCollection);

        $discountQueryContainerMock
            ->expects($this->once())
            ->method('querySalesDiscount')
            ->willReturn($salesDiscountQueryMock);

        return new ProductOptionDiscounts($discountQueryContainerMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpySalesDiscountQuery::class)
            ->setMethods(['filterByFkSalesOrderItem', 'where', 'find'])
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
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPriceWithProductOptions(900);
        $itemTransfer->setSumGrossPriceWithProductOptions(900);
        $itemTransfer->setRefundableAmount(900);
        $itemTransfer->setIdSalesOrderItem(1);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setUnitGrossPrice(100);
        $productOptionTransfer->setSumGrossPrice(100);
        $productOptionTransfer->setIdSalesOrderItemOption(1);
        $productOptionTransfer->setQuantity(1);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setUnitGrossPrice(200);
        $productOptionTransfer->setSumGrossPrice(200);
        $productOptionTransfer->setIdSalesOrderItemOption(1);
        $productOptionTransfer->setQuantity(1);
        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function createDiscountCollection()
    {
        $objectCollection = new ObjectCollection();

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(50);
        $salesDiscountEntity->setFkSalesOrderItemOption(1);
        $objectCollection->append($salesDiscountEntity);

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(50);
        $salesDiscountEntity->setFkSalesOrderItemOption(1);
        $objectCollection->append($salesDiscountEntity);

        return $objectCollection;
    }

}
