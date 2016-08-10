<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\SalesAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductOption\Business\SalesAggregator\ItemProductOptionGrossPrice;
use Unit\Spryker\Zed\ProductOption\MockProvider;

class ItemProductOptionGrossPriceTest extends MockProvider
{

    /**
     * @return void
     */
    public function testAggregateShouldSumAllOptionsPersistedWithOrder()
    {
        $itemProductOptionGrossPriceAggregatorMock = $this->createItemProductOptionGrossPriceAggregator();

        $propelObjectCollection = new ObjectCollection();

        $orderItemEntity = new SpySalesOrderItem();
        $orderItemEntity->setIdSalesOrderItem(1);

        $orderItemOptionEntity1 = new SpySalesOrderItemOption();
        $orderItemOptionEntity1->setFkSalesOrderItem(1);
        $orderItemOptionEntity1->setGrossPrice(100);
        $orderItemEntity->addOption($orderItemOptionEntity1);

        $orderItemOptionEntity2 = new SpySalesOrderItemOption();
        $orderItemOptionEntity2->setFkSalesOrderItem(1);
        $orderItemOptionEntity2->setGrossPrice(200);
        $orderItemEntity->addOption($orderItemOptionEntity2);

        $propelObjectCollection->append($orderItemEntity);

        $itemProductOptionGrossPriceAggregatorMock->expects($this->once())
            ->method('getSalesOrderItems')
            ->willReturn($propelObjectCollection);

        $oderTransfer = new OrderTransfer();
        $oderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem(1);
        $itemTransfer->setQuantity(1);

        $oderTransfer->addItem($itemTransfer);

        $itemProductOptionGrossPriceAggregatorMock->aggregate($oderTransfer);

        $optionTransfer1 = $itemTransfer->getProductOptions()[0];
        $optionTransfer2 = $itemTransfer->getProductOptions()[1];

        $this->assertSame($orderItemOptionEntity1->getGrossPrice(), $optionTransfer1->getUnitGrossPrice());
        $this->assertSame($orderItemOptionEntity2->getGrossPrice(), $optionTransfer2->getUnitGrossPrice());

        $this->assertSame($orderItemOptionEntity1->getGrossPrice(), $optionTransfer1->getSumGrossPrice());
        $this->assertSame($orderItemOptionEntity2->getGrossPrice(), $optionTransfer2->getSumGrossPrice());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\SalesAggregator\ItemProductOptionGrossPrice
     */
    protected function createItemProductOptionGrossPriceAggregator()
    {
        $salesQueryContainer = $this->createSalesContainerMock();

        return $this->getMockBuilder(ItemProductOptionGrossPrice::class)
            ->setConstructorArgs([$salesQueryContainer])
            ->setMethods(['getSalesOrderItems'])
            ->getMock();
    }

}
