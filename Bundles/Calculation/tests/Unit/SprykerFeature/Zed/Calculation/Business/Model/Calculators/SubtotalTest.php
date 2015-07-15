<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group SubtotalTest
 * @group Calculation
 */
class SubtotalTest extends \PHPUnit_Framework_TestCase
{

    const ITEM_GROSS_PRICE = 10000;

    public function testSubtotalShouldBeZeroForAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $order->getCalculableObject()->setItems(new \ArrayObject());

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(0, $totalsTransfer->getSubtotal());
    }

    public function testSubtotalShouldBeMoreThanZeroForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setQuantity(1);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    public function testSubtotalShouldReturnTwiceTheItemGrossPriceForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(2 * self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return OrderItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new OrderItemTransfer();

        return $item;
    }

    /**
     * @return TotalsTransfer
     */
    protected function getTotals()
    {
        return new TotalsTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
