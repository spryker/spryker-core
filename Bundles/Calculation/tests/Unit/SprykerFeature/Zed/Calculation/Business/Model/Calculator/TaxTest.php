<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
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
    const ITEM_GROSS_PRICE_450 = 450;
    const TAX_PERCENTAGE_10 = 10;
    const TAX_PERCENTAGE_20 = 20;
    const TAX_PERCENTAGE_30 = 30;
    const ID_TAX_SET_1 = 123;
    const ID_TAX_SET_2 = 224;
    const ID_TAX_RATE_1 = 345;
    const ID_TAX_RATE_2 = 456;
    const ID_TAX_RATE_3 = 567;
    const KEY_TAX_RATES = 'tax_rates';
    const KEY_PERCENTAGE = 'percentage';
    const KEY_AMOUNT = 'amount';

    public function testTaxCalculatedForOrderWithOrderItemAndSingleTaxSet()
    {
        $orderTransfer = $this->getOrderTransfer();
        $orderItemTransfer = $this->getOrderItemTransfer();

        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_10)
            ->setIdTaxRate(self::ID_TAX_SET_1);
        $taxRate20 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_20)
            ->setIdTaxRate(self::ID_TAX_SET_2);

        $taxSetTransfer = (new TaxSetTransfer)
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10)
            ->addTaxRate($taxRate20);

        $orderItemTransfer->setTaxSet($taxSetTransfer)
           ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
           ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $orderTransfer->getCalculableObject()->addItem($orderItemTransfer);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $orderTransfer, $orderTransfer->getCalculableObject()->getItems());

        $taxSets = $totalsTransfer->getTaxTotal()->getTaxSets();

        $this->assertEquals(258, $taxSets[0]->getAmount());
        $this->assertEquals(91, $taxSets[0]->getTaxRates()[0]->getAmount());
        $this->assertEquals(167, $taxSets[0]->getTaxRates()[1]->getAmount());
    }

    public function testTaxCalculatedForOrderWithMultipleOrderItemsAndMultipleTaxSets()
    {
        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_10)
            ->setIdTaxRate(self::ID_TAX_RATE_1);
        $taxRate20 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_20)
            ->setIdTaxRate(self::ID_TAX_RATE_2);

        $taxSetTransfer1 = (new TaxSetTransfer)
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10)
            ->addTaxRate($taxRate20);

        $taxSetTransfer2 = clone $taxSetTransfer1;

        $taxRate30 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_30)
            ->setIdTaxRate(self::ID_TAX_RATE_3);

        $taxSetTransfer3 = (new TaxSetTransfer)
            ->setIdTaxSet(self::ID_TAX_SET_2)
            ->addTaxRate($taxRate30);

        $orderTransfer = $this->getOrderTransfer();

        $orderItemTransfer1 = $this->getOrderItemTransfer();
        $orderItemTransfer1->setTaxSet($taxSetTransfer1)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $orderTransfer->getCalculableObject()->addItem($orderItemTransfer1);

        $orderItemTransfer2 = $this->getOrderItemTransfer();
        $orderItemTransfer2->setTaxSet($taxSetTransfer2)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_450)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_450);
        $orderTransfer->getCalculableObject()->addItem($orderItemTransfer2);

        $orderItemTransfer3 = $this->getOrderItemTransfer();
        $orderItemTransfer3->setTaxSet($taxSetTransfer3)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $orderTransfer->getCalculableObject()->addItem($orderItemTransfer3);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $orderTransfer, $orderTransfer->getCalculableObject()->getItems());

        $groupedTaxSets = $totalsTransfer->getTaxTotal()->getTaxSets();

        $this->assertCount(2, $groupedTaxSets);
        $this->assertEquals(335, $groupedTaxSets[0]->getAmount());
        $this->assertEquals(231, $groupedTaxSets[1]->getAmount());
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
    private function getOrderTransfer()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return OrderItemTransfer
     */
    private function getOrderItemTransfer()
    {
        $item = new OrderItemTransfer();

        return $item;
    }

    /**
     * @return ExpenseTransfer
     */
    private function getExpenseTransfer()
    {
        $expense = new ExpenseTransfer();

        return $expense;
    }

    /**
     * @return TotalsTransfer
     */
    private function getTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
