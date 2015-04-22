<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\Factory;

/**
 * Class ExpenseTest
 * @group ExpenseTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Communication\Plugin
 */
class ExpenseTest extends \PHPUnit_Framework_TestCase
{
    const EXPENSE_1000 = 1000;
    const SALES_DISCOUNT_100 = 100;
    const SALES_DISCOUNT_50 = 50;

    public function testExpensePriceToPayShouldBeTheSameAsTheGrossPriceForNoExpenseDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item = $this->getItemWithFixtureData();
        $item->addExpense($expense);

        $order->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals($expense->getGrossPrice(), $expense->getPriceToPay());
            }
        }
    }

    public function testExpensePriceToPayShouldBeZeroIfExpenseDiscountAmountIsExpenseGrossPrice()
    {
        $order = $this->getOrderWithFixtureData();
        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item = $this->getItemWithFixtureData();
        $item->addExpense($expense);

        $discount = $this->getPriceDiscount();
        $discount->setAmount($expense->getGrossPrice());

        $expense->addDiscount($discount);
        $order->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals(0, $expense->getPriceToPay());
            }
        }
    }

    public function testExpensePriceToPayShouldBeHalfOfTheExpenseGrossPriceForTwoDiscountsWithAQuaterOfTheGrossPriceEach()
    {
        $order = $this->getOrderWithFixtureData();
        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item = $this->getItemWithFixtureData();
        $item->addExpense($expense);

        $discount = $this->getPriceDiscount();
        $discount->setAmount($expense->getGrossPrice()/4);

        $expense->addDiscount($discount);
        $expense->addDiscount(clone $discount);

        $order->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals($expense->getGrossPrice()/2, $expense->getPriceToPay());
            }
        }
    }

    /**
     * @return Discount
     */
    protected function getPriceDiscount()
    {
        return $this->getLocator()->calculation()->transferDiscount();
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
     * @return Expense
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = $this->getLocator()->calculation()->transferExpense();
        $expense->fillWithFixtureData();

        return $expense;
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }
}
