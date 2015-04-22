<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class SubtotalTest
 * @group SubtotalTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
class SubtotalTest extends \PHPUnit_Framework_TestCase
{
    const ITEM_GROSS_PRICE = 10000;

    public function testSubtotalShouldBeZeroForAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());
        $this->assertEquals(0, $totalsTransfer->getSubtotal());
    }

    public function testSubtotalShouldBeMoreThanZeroForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());
        $this->assertEquals(self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    public function testSubtotalShouldReturnTwiceTheItemGrossPriceForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);
        $order->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());
        $this->assertEquals(2 * self::ITEM_GROSS_PRICE, $totalsTransfer->getSubtotal());
    }

    /**
     * @return Order
     */
    protected function getOrderWithFixtureData()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->fillWithFixtureData();

        return $order;
    }

    /**
     * @return OrderItem
     */
    protected function getItemWithFixtureData()
    {
        $item = $this->getLocator()->sales()->transferOrderItem();
        $item->fillWithFixtureData();

        return $item;
    }

    /**
     * @return TotalsInterface
     */
    protected function getTotals()
    {
        return $this->getLocator()->calculation()->transferTotals();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }
}
