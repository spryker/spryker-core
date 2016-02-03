<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\ExpenseTotalsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationBridge;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

/**
 * Class GrandTotalTest
 *
 * @group GrandTotalTest
 * @group Calculation
 */
class GrandTotalWithDiscountTest extends Test
{

    const ITEM_GROSS_PRICE = 10000;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 1000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 1000;
    const ORDER_SHIPPING_COSTS = 2000;
    const EXPENSE_NAME_SHIPPING_COSTS = 'Shipping Costs';
    const EXPENSE_TYPE_SHIPPING = 'shipping';

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

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(0, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldBeModeThanZeroForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);

        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(self::ITEM_GROSS_PRICE, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldReturnTwiceTheItemGrossPriceForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(2 * self::ITEM_GROSS_PRICE, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldReturnTheItemGrossPriceAndShippingCostsForAnOrderWithTwoItemsAndExpenseOnItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setName(self::EXPENSE_NAME_SHIPPING_COSTS)
            ->setType(self::EXPENSE_TYPE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS / 2)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS / 2);
        $item->addExpense($expense);

        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(2 * self::ITEM_GROSS_PRICE + self::ORDER_SHIPPING_COSTS, $totalsTransfer->getGrandTotalWithDiscounts());
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldReturnTheItemGrossPriceMinusTheItemCouponDiscountForAnOrderWithOneItemAndACouponDiscount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);

        $discount = $this->getDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);

        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(
            self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT,
            $totalsTransfer->getGrandTotalWithDiscounts()
        );
    }

    /**
     * @return void
     */
    public function testGrandTotalShouldReturnTheItemGrossPriceMinusTheItemSalesurleDiscountForAnOrderWithTwoItemsAndADiscountAndACouponDiscount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setQuantity(1);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discount = $this->getDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $totalsTransfer = $this->getTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $this->assertEquals(
            2 * (self::ITEM_GROSS_PRICE - self::ITEM_SALESRULE_DISCOUNT_AMOUNT - self::ITEM_COUPON_DISCOUNT_AMOUNT),
            $totalsTransfer->getGrandTotalWithDiscounts()
        );
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator
     */
    protected function getCalculator()
    {
        return new GrandTotalWithDiscountsTotalsCalculator(
            new DiscountCalculationToCalculationBridge(new CalculationFacade()),
            new DiscountTotalsCalculator()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotals()
    {
        $totals = new TotalsTransfer();
        $totals->setDiscount(new DiscountTotalsTransfer());
        $totals->setExpenses(new ExpenseTotalsTransfer());

        return $totals;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function getDiscount()
    {
        return new DiscountTransfer();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();
        $totals = new TotalsTransfer();
        $totals->setDiscount(new DiscountTotalsTransfer());
        $order->setTotals($totals);

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
