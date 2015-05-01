<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelper;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class TaxTest
 * @group TaxTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Business\Model\Calculator
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

        $this->assertEquals(10, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_PERCENTAGE]);
        $this->assertEquals(91, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_AMOUNT]);
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

        $this->assertEquals(10, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_PERCENTAGE]);
        $this->assertEquals(91, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_AMOUNT]);
        $this->assertEquals(20, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-2][self::KEY_PERCENTAGE]);
        $this->assertEquals(167, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-2][self::KEY_AMOUNT]);
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

        $this->assertEquals(10, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_PERCENTAGE]);
        $this->assertEquals(182, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-1][self::KEY_AMOUNT]);
        $this->assertEquals(20, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-2][self::KEY_PERCENTAGE]);
        $this->assertEquals(167, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-2][self::KEY_AMOUNT]);
        $this->assertEquals(30, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-3][self::KEY_PERCENTAGE]);
        $this->assertEquals(231, $totalsTransfer->getTax()->toArray()[self::KEY_TAX_RATES][-3][self::KEY_AMOUNT]);
    }

    /**
     * @return TaxTotalsCalculator
     */
    private function getCalculator()
    {
        return new TaxTotalsCalculator($this->getLocator(), new PriceCalculationHelper());
    }

    /**
     * @return Order
     */
    protected function getOrderWithFixtureData()
    {
        /* @var Order $order */
        $order = new \Generated\Shared\Transfer\SalesOrderTransfer();
        $order->fillWithFixtureData();

        return $order;
    }

    /**
     * @return OrderItem
     */
    protected function getItemWithFixtureData()
    {
        $item = new \Generated\Shared\Transfer\SalesOrderItemTransfer();
        $item->fillWithFixtureData();

        return $item;
    }

    /**
     * @return Expense
     */
    protected function getExpenseWithFixtureData()
    {
        $expense = new \Generated\Shared\Transfer\CalculationExpenseTransfer();
        $expense->fillWithFixtureData();

        return $expense;
    }

    /**
     * @return TotalsInterface
     */
    protected function getPriceTotals()
    {
        return new \Generated\Shared\Transfer\CalculationTotalsTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }
}
