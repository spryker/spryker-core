<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\Factory;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Expense
 */
class ExpenseTest extends AbstractUnitTest
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
        $calculator->setOwnFacade($this->getFacade());
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

        $expense->addDiscountItem($discount);
        $order->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator());
        $calculator->setOwnFacade($this->getFacade());
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

        $expense->addDiscountItem($discount);
        $expense->addDiscountItem(clone $discount);

        $order->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator());
        $calculator->setOwnFacade($this->getFacade());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals($expense->getGrossPrice()/2, $expense->getPriceToPay());
            }
        }
    }

    /**
     * @return DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new DiscountTransfer();
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
     * @return ExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = new ExpenseTransfer();

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
