<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\ExpenseTotalsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationBridge;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

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
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldBeZeroForAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getGrandTotalCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());
        $this->assertEquals(0, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator
     */
    private function getGrandTotalCalculator()
    {
        return new GrandTotalTotalsCalculator(
            new SubtotalTotalsCalculator(),
            new ExpenseTotalsCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator
     */
    protected function getGrandTotalWithDiscountCalculator()
    {
        return new GrandTotalWithDiscountsTotalsCalculator(
            new DiscountCalculationToCalculationBridge(new CalculationFacade()),
            new DiscountTotalsCalculator()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getPriceTotals()
    {
        $totals = new TotalsTransfer();
        $totals->setExpenses(new ExpenseTotalsTransfer());
        $totals->setDiscount(new DiscountTotalsTransfer());

        return $totals;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new DiscountTransfer();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CalculableContainer
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
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new ItemTransfer();

        return $item;
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = new ExpenseTransfer();

        return $expense;
    }

}
