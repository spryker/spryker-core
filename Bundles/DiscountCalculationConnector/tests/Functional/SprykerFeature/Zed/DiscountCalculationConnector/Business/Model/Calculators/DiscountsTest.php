<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * Class DiscountsTest
 *
 * @group DiscountTest
 * @group Calculation
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
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item->addExpense($expense);
        $order->getCalculableObject()->addItem($item);

        $calculator = new DiscountTotalsCalculator(Locator::getInstance());
        $totals = new TotalsTransfer();
        $calculator->recalculateTotals($totals, $order, $order->getCalculableObject()->getItems());

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
        $order->getCalculableObject()->addItem($item);
        $totals = $this->getPriceTotals();

        $calculator = new DiscountTotalsCalculator(Locator::getInstance());

        $calculator->recalculateTotals($totals, $order, $order->getCalculableObject()->getItems());

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
        $expense->addDiscountItem($expenseDiscount);

        $item->addExpense($expense);

        $discount = $this->getPriceDiscount();
        $discount->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discount->setAmount(self::SALES_DISCOUNT_100);
        $item->addDiscount($discount);
        $order->getCalculableObject()->addItem($item);

        $totals = $this->getPriceTotals();
        $calculator = new DiscountTotalsCalculator(Locator::getInstance());
        $calculator->recalculateTotals($totals, $order, $order->getCalculableObject()->getItems());

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
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        return new CalculableContainer(new OrderTransfer());
    }

    /**
     * @return OrderItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        return new OrderItemTransfer();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return TotalsTransfer
     */
    protected function getPriceTotals()
    {
        return new TotalsTransfer();
    }

    /**
     * @return DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new DiscountTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
