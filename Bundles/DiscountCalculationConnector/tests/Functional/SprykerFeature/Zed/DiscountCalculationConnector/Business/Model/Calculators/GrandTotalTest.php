<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\ExpenseTotalsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * Class GrandTotalWithoutDiscountsTest
 *
 * @group GrandTotalWithoutDiscountsTest
 * @group Calculation
 */
class GrandTotalTest extends Test
{

    const ITEM_GROSS_PRICE = 10000;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 1000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 1000;
    const ORDER_SHIPPING_COSTS = 2000;

    /**
     * @var LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
    }

    public function testGrandTotalShouldBeZeroForAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getGrandTotalCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(0, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    public function testGrandTotalWithoutDiscountsShouldNotBeReducedByTheDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $itemCollection = new OrderItemsTransfer();
        $itemCollection->addOrderItem($item);
        $order->getCalculableObject()->setItems($itemCollection);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getGrandTotalCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(self::ITEM_GROSS_PRICE, $totalsTransfer->getGrandTotal());
    }

    public function testGrandTotalWithoutDiscountsShouldBeByTheDiscountAmountReducedComparedToTheGrandTotal()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $itemCollection = new OrderItemsTransfer();
        $itemCollection->addOrderItem($item);
        $order->getCalculableObject()->setItems($itemCollection);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getGrandTotalCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $calculator = $this->getGrandTotalWithDiscountCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(
            $totalsTransfer->getGrandTotal() - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
            $totalsTransfer->getGrandTotalWithDiscounts()
        );
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    private function getGrandTotalCalculator()
    {
        return new GrandTotalTotalsCalculator(
            new SubtotalTotalsCalculator(),
            new ExpenseTotalsCalculator()
        );
    }

    /**
     * @return GrandTotalWithDiscountsTotalsCalculator
     */
    protected function getGrandTotalWithDiscountCalculator()
    {
        return new GrandTotalWithDiscountsTotalsCalculator(
            $this->locator->calculation()->facade(),
            new DiscountTotalsCalculator()
        );
    }

    /**
     * @return TotalsTransfer
     */
    protected function getPriceTotals()
    {
        $totals = new TotalsTransfer();
        $totals->setExpenses(new ExpenseTotalsTransfer());
        $totals->setDiscount(new DiscountTotalsTransfer());

        return $totals;
    }

    /**
     * @return DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new DiscountTransfer();
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();
        $order->setItems(new OrderItemsTransfer());

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setExpenses(new ExpenseTotalsTransfer());
        $order->setTotals($totalsTransfer);

        $order->setExpenses(new ExpenseTransfer());

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
     * @return ExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = new ExpenseTransfer();

        return $expense;
    }

}
