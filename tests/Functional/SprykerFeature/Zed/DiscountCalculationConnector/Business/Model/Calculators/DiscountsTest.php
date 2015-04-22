<?php

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Shared\Calculation\Transfer\Discount;

/**
 * Class DiscountsTest
 * @group DiscountTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Communication\Plugin
 */
class DiscountsTest extends Test
{
    const EXPENSE_1000 = 1000;
    const SALES_DISCOUNT_100 = 100;
    const SALES_DISCOUNT_50 = 50;
    const ITEM_GROSS_PRICE_1000 = 1000;
    const DISCOUNT_DISPLAY_NAME = 'Name';

    public function testDiscountShouldBeZeroForItemsAndExpensesWithoutAnyDiscount()
    {
        $locator = $this->getLocator();
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item->addExpense($expense);
        $order->addItem($item);

        $calculator = new DiscountTotalsCalculator(Locator::getInstance());
        $totals = $locator->calculation()->transferTotals();
        $calculator->recalculateTotals($totals, $order, $order->getItems());

        $this->assertEquals(0, $totals->getDiscount()->getTotalAmount());
        $this->assertCount(0, $totals->getDiscount()->getDiscountItems());
    }

    public function testDiscountShouldBeItemDiscountForOnlyDiscountedItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $discount = $this->getPriceDiscount();
        $discount->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discount->setAmount(self::SALES_DISCOUNT_100);

        $item->addDiscount($discount);
        $order->addItem($item);
        $totals = $this->getPriceTotals();

        $calculator = new DiscountTotalsCalculator(Locator::getInstance());

        $calculator->recalculateTotals($totals, $order, $order->getItems());

        $this->assertEquals(self::SALES_DISCOUNT_100, $totals->getDiscount()->getTotalAmount());
        $this->assertCount(1, $totals->getDiscount()->getDiscountItems());

        foreach ($totals->getDiscount()->getDiscountItems() as $item) {
            $this->assertEquals(self::DISCOUNT_DISPLAY_NAME, $item->getName());
            $this->assertEquals(self::SALES_DISCOUNT_100, $item->getAmount());
        }
    }

    public function testDiscountShouldBeItemDiscountAndExpenseDiscountForDiscountedItemsAndExpenses()
    {
        $order = $this->getOrderWithFixtureData();
        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $expense = $this->getExpenseWithFixtureData();

        $expenseDiscount = $this->getPriceDiscount();
        $expenseDiscount->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $expenseDiscount->setAmount(self::SALES_DISCOUNT_50);
        $expense->setGrossPrice(self::EXPENSE_1000);
        $expense->addDiscount($expenseDiscount);

        $item->addExpense($expense);

        $discount = $this->getPriceDiscount();
        $discount->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discount->setAmount(self::SALES_DISCOUNT_100);
        $item->addDiscount($discount);
        $order->addItem($item);

        $totals = $this->getPriceTotals();
        $calculator = new DiscountTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totals, $order, $order->getItems());

        $this->assertEquals(
            self::SALES_DISCOUNT_50 + self::SALES_DISCOUNT_100,
            $totals->getDiscount()->getTotalAmount()
        );

        foreach ($totals->getDiscount()->getDiscountItems() as $item) {
            $this->assertEquals(self::DISCOUNT_DISPLAY_NAME, $item->getName());
            $this->assertEquals(self::SALES_DISCOUNT_50 + self::SALES_DISCOUNT_100, $item->getAmount());
        }
    }

    /**
     * @return Order
     */
    protected function getOrderWithFixtureData()
    {
        /* @var Order $order */
        $order = $this->getLocator()->sales()->transferOrder();
        $order->fillWithFixtureData();

        return $order;
    }

    /**
     * @return OrderItem
     */
    protected function getItemWithFixtureData()
    {
        /* @var OrderItem $item */
        $item = $this->getLocator()->sales()->transferOrderItem();
        $item->fillWithFixtureData();

        return $item;
    }

    /**
     * @return Expense
     */
    protected function getExpenseWithFixtureData()
    {
        /* @var Expense $expense */
        $expense = $this->getLocator()->calculation()->transferExpense();
        $expense->fillWithFixtureData();

        return $expense;
    }

    /**
     * @return TotalsInterface
     */
    protected function getPriceTotals()
    {
        return $this->getLocator()->calculation()->transferTotals();
    }

    /**
     * @return Discount
     */
    protected function getPriceDiscount()
    {
        return $this->getLocator()->calculation()->transferDiscount();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }
}
