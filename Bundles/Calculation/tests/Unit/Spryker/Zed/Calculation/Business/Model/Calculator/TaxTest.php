<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

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

    /**
     * @return void
     */
    public function testTaxCalculatedForOrderWithOrderItemAndSingleTaxSet()
    {
        $orderTransfer = $this->getOrderTransfer();
        $itemTransfer = $this->getItemTransfer();

        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_10)
            ->setIdTaxRate(self::ID_TAX_SET_1);
        $taxRate20 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_20)
            ->setIdTaxRate(self::ID_TAX_SET_2);

        $taxSetTransfer = (new TaxSetTransfer())
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10)
            ->addTaxRate($taxRate20);

        $itemTransfer->setTaxSet($taxSetTransfer)
           ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
           ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $orderTransfer->getCalculableObject()->addItem($itemTransfer);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $orderTransfer, $orderTransfer->getCalculableObject()->getItems());

        $taxSets = $totalsTransfer->getTaxTotal()->getTaxSets();

        $this->assertEquals(231, $taxSets[0]->getAmount());
        $this->assertEquals(30, $taxSets[0]->getEffectiveRate());
    }

    /**
     * @return void
     */
    public function testTaxCalculatedForOrderWithMultipleOrderItemsAndMultipleTaxSets()
    {
        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_10)
            ->setIdTaxRate(self::ID_TAX_RATE_1);
        $taxRate20 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_20)
            ->setIdTaxRate(self::ID_TAX_RATE_2);

        $taxSetTransfer1 = (new TaxSetTransfer())
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10)
            ->addTaxRate($taxRate20);

        $taxSetTransfer2 = clone $taxSetTransfer1;

        $taxRate30 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_30)
            ->setIdTaxRate(self::ID_TAX_RATE_3);

        $taxSetTransfer3 = (new TaxSetTransfer())
            ->setIdTaxSet(self::ID_TAX_SET_2)
            ->addTaxRate($taxRate30);

        $orderTransfer = $this->getOrderTransfer();

        $itemTransfer1 = $this->getItemTransfer();
        $itemTransfer1->setTaxSet($taxSetTransfer1)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $orderTransfer->getCalculableObject()->addItem($itemTransfer1);

        $itemTransfer2 = $this->getItemTransfer();
        $itemTransfer2->setTaxSet($taxSetTransfer2)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_450)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_450);
        $orderTransfer->getCalculableObject()->addItem($itemTransfer2);

        $itemTransfer3 = $this->getItemTransfer();
        $itemTransfer3->setTaxSet($taxSetTransfer3)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);
        $orderTransfer->getCalculableObject()->addItem($itemTransfer3);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $orderTransfer, $orderTransfer->getCalculableObject()->getItems());

        $groupedTaxSets = $totalsTransfer->getTaxTotal()->getTaxSets();

        $this->assertCount(2, $groupedTaxSets);
        $this->assertEquals(335, $groupedTaxSets[0]->getAmount());
        $this->assertEquals(30, $groupedTaxSets[0]->getEffectiveRate());
        $this->assertEquals(231, $groupedTaxSets[1]->getAmount());
        $this->assertEquals(30, $groupedTaxSets[0]->getEffectiveRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxOnExpenses()
    {
        $caslculableContainer = $this->getOrderTransfer();
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $caslculableContainer->getCalculableObject();
        $itemTransfer = $this->getItemTransfer();
        $orderExpenseTransfer = $this->getExpenseTransfer();
        $orderItemExpenseTransfer = $this->getExpenseTransfer();

        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_30)
            ->setIdTaxRate(self::ID_TAX_SET_1);
        $taxSetTransfer = (new TaxSetTransfer())
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10);

        $itemTransfer->setTaxSet($taxSetTransfer)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $orderExpenseTransfer->setTaxSet($taxSetTransfer)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $orderItemExpenseTransfer->setTaxSet($taxSetTransfer)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $itemTransfer->addExpense($orderItemExpenseTransfer);
        $orderTransfer->addItem($itemTransfer);
        $orderTransfer->addExpense($orderExpenseTransfer);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $caslculableContainer, $orderTransfer->getItems());

        $orderExpenseeTaxSet = $orderTransfer->getExpenses()[0]->getTaxSet();
        $orderItemTaxSet = $itemTransfer->getTaxSet();
        $orderItemExpenseTaxSet = $itemTransfer->getExpenses()[0]->getTaxSet();

        $this->assertEquals(231, $orderExpenseeTaxSet->getAmount());

        $this->assertEquals(231, $orderItemTaxSet->getAmount());
        $this->assertEquals(231, $orderItemExpenseTaxSet->getAmount());

        $groupedTaxSets = $totalsTransfer->getTaxTotal()->getTaxSets();
        $this->assertCount(1, $groupedTaxSets);
        $this->assertEquals(462, $groupedTaxSets[0]->getAmount());
        $this->assertEquals(30, $groupedTaxSets[0]->getEffectiveRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxOnProductOption()
    {
        $caslculableContainer = $this->getOrderTransfer();
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $caslculableContainer->getCalculableObject();
        $itemTransfer = $this->getItemTransfer();
        $optionTransfer = $this->getProductOptionTransfer();

        $taxRate10 = (new TaxRateTransfer())
            ->setRate(self::TAX_PERCENTAGE_30)
            ->setIdTaxRate(self::ID_TAX_SET_1);
        $taxSetTransfer = (new TaxSetTransfer())
            ->setIdTaxSet(self::ID_TAX_SET_1)
            ->addTaxRate($taxRate10);

        $itemTransfer->setTaxSet($taxSetTransfer)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $optionTransfer->setTaxSet($taxSetTransfer)
            ->setGrossPrice(self::ITEM_GROSS_PRICE_1000)
            ->setPriceToPay(self::ITEM_GROSS_PRICE_1000);

        $itemTransfer->addProductOption($optionTransfer);
        $orderTransfer->addItem($itemTransfer);

        $totalsTransfer = $this->getTotalsTransfer();
        $calculator = $this->getCalculator();

        $calculator->recalculateTotals($totalsTransfer, $caslculableContainer, $orderTransfer->getItems());

        $orderItemTaxSet = $itemTransfer->getTaxSet();
        $orderItemOptionTaxSet = $itemTransfer->getProductOptions()[0]->getTaxSet();

        $this->assertEquals(231, $orderItemTaxSet->getAmount());
        $this->assertEquals(30, $orderItemTaxSet->getEffectiveRate());
        $this->assertEquals(231, $orderItemOptionTaxSet->getAmount());

        $groupedTaxSets = $totalsTransfer->getTaxTotal()->getTaxSets();
        $this->assertCount(1, $groupedTaxSets);
        $this->assertEquals(231, $groupedTaxSets[0]->getAmount());
        $this->assertEquals(30, $orderItemTaxSet->getEffectiveRate());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator
     */
    private function getCalculator()
    {
        return new TaxTotalsCalculator(new PriceCalculationHelper());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CalculableContainer
     */
    private function getOrderTransfer()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    private function getItemTransfer()
    {
        $item = new ItemTransfer();

        return $item;
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    private function getExpenseTransfer()
    {
        $expense = new ExpenseTransfer();

        return $expense;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    private function getProductOptionTransfer()
    {
        $option = new ProductOptionTransfer();

        return $option;
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    private function getTotalsTransfer()
    {
        return new TotalsTransfer();
    }

}
