<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group SubtotalTest
 * @group Calculation
 */
class SubtotalTest extends \PHPUnit_Framework_TestCase
{

    const ITEM_GROSS_PRICE = 10000;

    /**
     * @return void
     */
    public function testSubtotalShouldBeZeroForAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $order->getCalculableObject()->setItems(new \ArrayObject());

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(0, $totalsTransfer->getSubtotal());
    }

    /**
     * @return void
     */
    public function testSubtotalShouldBeMoreThanZeroForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setQuantity(1);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    /**
     * @return void
     */
    public function testSubtotalShouldReturnTwiceTheItemGrossPriceForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(2 * self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new ItemTransfer();

        return $item;
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotals()
    {
        return new TotalsTransfer();
    }

}
