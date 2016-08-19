<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Item;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group SalesAggregator
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group OrderItemTest
 */
class OrderItemTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemAmountAggregatorShouldSetSumGrossPrice()
    {
        $itemAggregator = $this->createItemAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemAggregator->aggregate($orderTransfer);

        $this->assertEquals(200, $orderTransfer->getItems()[0]->getSumGrossPrice());
    }

    /**
     * @return void
     */
    public function testRefundableAmountShouldBeSetSameAsSumGrossPrice()
    {
        $itemAggregator = $this->createItemAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemAggregator->aggregate($orderTransfer);

        $this->assertEquals(200, $orderTransfer->getItems()[0]->getRefundableAmount());
    }

    /**
     * @return void
     */
    public function testRefundableAmountWhenPartialyCancelledShouldSubstractFromRefundable()
    {
        $itemAggregator = $this->createItemAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTransfer->getItems()[0]->setCanceledAmount(100);
        $itemAggregator->aggregate($orderTransfer);

        $this->assertEquals(100, $orderTransfer->getItems()[0]->getRefundableAmount());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Item
     */
    protected function createItemAggregator()
    {
        return new Item();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2)
            ->setUnitGrossPrice(100);
        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

}
