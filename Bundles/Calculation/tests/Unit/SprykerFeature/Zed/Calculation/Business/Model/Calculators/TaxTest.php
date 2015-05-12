<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculationTotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\SalesOrderTransfer;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\CalculationExpenseTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelper;
use SprykerEngine\Zed\Kernel\Locator;

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
        $item->setTaxPercentage(self::TAX_PERCENTAGE_10);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->addItem($item);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());

        $taxRates = $totalsTransfer->getTax()->getTaxRates();

        $this->assertEquals(10, $taxRates[0]->getPercentage());
        $this->assertEquals(91, $taxRates[0]->getAmount());
    }

    public function testShouldHaveProperlyCalculatedTaxAmountsForAnOrderWithTwoItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setTaxPercentage(self::TAX_PERCENTAGE_10);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->addItem($item);

        $item = $this->getItemWithFixtureData();
        $item->setTaxPercentage(self::TAX_PERCENTAGE_20);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->addItem($item);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());

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
        $item->setTaxPercentage(self::TAX_PERCENTAGE_10);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->addItem($item);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);
        $expense->setPriceToPay(self::EXPENSE_1000);
        $expense->setTaxPercentage(self::TAX_PERCENTAGE_10);
        $item->addExpense($expense);

        $item = $this->getItemWithFixtureData();
        $item->setTaxPercentage(self::TAX_PERCENTAGE_20);
        $item->setGrossPrice(self::ITEM_GROSS_PRICE_1000);
        $item->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $order->addItem($item);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setGrossPrice(self::EXPENSE_1000);
        $expense->setPriceToPay(self::EXPENSE_1000);
        $expense->setTaxPercentage(self::TAX_PERCENTAGE_30);
        $order->addExpense($expense);

        $totalsTransfer = $this->getPriceTotals();
        $calculator = $this->getCalculator();
        $calculator->recalculateTotals($totalsTransfer, $order, $order->getItems());

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
     * @return SalesOrderTransfer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new SalesOrderTransfer();

        return $order;
    }

    /**
     * @return SalesOrderItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new SalesOrderItemTransfer();

        return $item;
    }

    /**
     * @return CalculationExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = new CalculationExpenseTransfer();

        return $expense;
    }

    /**
     * @return CalculationTotalsTransfer
     */
    protected function getPriceTotals()
    {
        return new CalculationTotalsTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }
}
