<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
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
        $order->setItems(new \ArrayObject());

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
     * @return OrderTransfer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return $order;
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
