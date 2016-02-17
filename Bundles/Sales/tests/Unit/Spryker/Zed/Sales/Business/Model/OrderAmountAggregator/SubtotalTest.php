<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Subtotal;

class SubtotalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSubtotalAmountShouldBeSumOfGrossItemAmounts()
    {
        $subtotalAggregator = $this->getSubtotalAggregator();

        $orderTransfer = $this->createOrderTransfer();
        $subtotalAggregator->aggregate($orderTransfer);

        $this->assertEquals(400, $orderTransfer->getTotals()->getSubtotal());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Subtotal
     */
    protected function getSubtotalAggregator()
    {
        return new Subtotal();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2)
            ->setSumGrossPrice(200);
        $orderTransfer->addItem($itemTransfer);

        $itemTransfer->setQuantity(2)
            ->setSumGrossPrice(200);

        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

}
