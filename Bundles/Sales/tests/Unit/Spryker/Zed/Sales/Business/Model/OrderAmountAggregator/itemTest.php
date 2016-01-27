<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
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
     * @return Item
     */
    protected function createItemAggregator()
    {
        return new Item();
    }

    /**
     * @return OrderTransfer
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
