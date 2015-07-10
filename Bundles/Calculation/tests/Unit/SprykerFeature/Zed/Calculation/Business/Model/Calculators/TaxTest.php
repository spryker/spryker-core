<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TaxItemTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelper;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group TaxTest
 * @group Calculation
 */
class TaxTest extends \PHPUnit_Framework_TestCase
{

    const EXPENSE_1000 = 1000;
    const ITEM_GROSS_PRICE_1000 = 1000;
    const TAX_PERCENTAGE_10 = 10;
    const TAX_PERCENTAGE_20 = 20;
    const TAX_PERCENTAGE_30 = 30;
    const KEY_TAX_RATES = 'tax_rates';
    const KEY_PERCENTAGE = 'percentage';
    const KEY_AMOUNT = 'amount';

    public function testShouldHaveProperlyCalculatedTaxAmountsForAnOrderWithJustOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_10);
        $tax->setAmount(self::ITEM_GROSS_PRICE_1000);
        $item->setTax($tax);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $taxRates = $totalsTransfer->getTax()->getTaxRates();

        $this->assertEquals(10, $taxRates[0]->getPercentage());
        $this->assertEquals(91, $taxRates[0]->getAmount());
    }

    public function testShouldHaveProperlyCalculatedTaxAmountsForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_10);
        $tax->setAmount(self::ITEM_GROSS_PRICE_1000);
        $item->setTax($tax);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->getCalculableObject()->addItem($item);

        $item = $this->getItemWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_20);
        $tax->setAmount(self::ITEM_GROSS_PRICE_1000);
        $item->setTax($tax);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->getCalculableObject()->addItem($item);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $taxRates = $totalsTransfer->getTax()->getTaxRates();

        $this->assertEquals(10, $taxRates[0]->getPercentage());
        $this->assertEquals(91, $taxRates[0]->getAmount());
        $this->assertEquals(20, $taxRates[1]->getPercentage());
        $this->assertEquals(167, $taxRates[1]->getAmount());
    }

    public function testShouldHaveProperlyCalculatedTaxAmountsForAnOrderWithTwoItemsWithItemExpensesAndOrderExpenses()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_10);
        $tax->setAmount(self::ITEM_GROSS_PRICE_1000);
        $item->setTax($tax);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->getCalculableObject()->addItem($item);

        $expense = $this->getExpenseWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_10);
        $tax->setAmount(self::EXPENSE_1000);
        $expense->setTax($tax);
        $expense->setGrossPrice(self::EXPENSE_1000);
        $expense->setPriceToPay(self::EXPENSE_1000);
        $item->addExpense($expense);

        $item = $this->getItemWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_20);
        $tax->setAmount(self::ITEM_GROSS_PRICE_1000);
        $item->setTax($tax);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->getCalculableObject()->addItem($item);

        $expense = $this->getExpenseWithFixtureData();
        $tax = new TaxItemTransfer();
        $tax->setPercentage(self::TAX_PERCENTAGE_30);
        $tax->setAmount(self::EXPENSE_1000);
        $expense->setTax($tax);
        $expense->setGrossPrice(self::EXPENSE_1000);
        $expense->setPriceToPay(self::EXPENSE_1000);
        $order->getCalculableObject()->addExpense($expense);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getCalculableObject()->getItems());

        $taxRates = $totalsTransfer->getTax()->getTaxRates();

        $this->assertEquals(10,  $taxRates[0]->getPercentage());
        $this->assertEquals(182, $taxRates[0]->getAmount());
        $this->assertEquals(20,  $taxRates[1]->getPercentage());
        $this->assertEquals(167, $taxRates[1]->getAmount());
        $this->assertEquals(30,  $taxRates[2]->getPercentage());
        $this->assertEquals(231, $taxRates[2]->getAmount());
    }

    /**
     * @return TaxTotalsCalculator
     */
    private function getCalculator()
    {
        return new TaxTotalsCalculator(new PriceCalculationHelper());
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

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

    /**
     * @return TotalsTransfer
     */
    protected function getPriceTotals()
    {
        return new TotalsTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
