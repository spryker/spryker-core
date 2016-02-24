<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Expense
 */
class ExpenseTest extends \PHPUnit_Framework_TestCase
{

    const EXPENSE_1000 = 1000;
    const SALES_DISCOUNT_100 = 100;
    const SALES_DISCOUNT_50 = 50;

    /**
     * @return void
     */
    public function testExpensePriceToPayShouldBeTheSameAsTheGrossPriceForNoExpenseDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item = $this->getItemWithFixtureData();
        $item->addExpense($expense);

        $order->getCalculableObject()->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin();
        $calculator->setFacade(new CalculationFacade());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals($expense->getGrossPrice(), $expense->getPriceToPay());
            }
        }
    }

    /**
     * @return void
     */
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
        $order->getCalculableObject()->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin();
        $calculator->setFacade(new CalculationFacade());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals(0, $expense->getPriceToPay());
            }
        }
    }

    /**
     * @return void
     */
    public function testExpensePriceToPayShouldBeHalfOfTheExpenseGrossPriceForTwoDiscountsWithAQuaterOfTheGrossPriceEach()
    {
        $order = $this->getOrderWithFixtureData();
        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);

        $item = $this->getItemWithFixtureData();
        $item->addExpense($expense);

        $discount = $this->getPriceDiscount();
        $discount->setAmount($expense->getGrossPrice() / 4);

        $expense->addDiscountItem($discount);
        $expense->addDiscountItem(clone $discount);

        $order->getCalculableObject()->addItem($item);

        $calculator = new ExpensePriceToPayCalculatorPlugin();
        $calculator->setFacade(new CalculationFacade());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->assertEquals($expense->getGrossPrice() / 2, $expense->getPriceToPay());
            }
        }
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
