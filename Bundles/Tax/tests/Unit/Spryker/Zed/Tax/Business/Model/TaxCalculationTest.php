<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;
use Spryker\Zed\Tax\Business\Model\TaxCalculation;

class TaxCalculationTest extends \PHPUnit_Framework_TestCase
{

    const TAX_RATE = 19;
    const GRAND_TOTAL = 100;

    /**
     * @return void
     */
    public function testCalculateTaxAmountWhenRateIsFixed()
    {
        $priceCalculationMock = $this->createPriceCalculationMock();

        $priceCalculationMock
            ->expects($this->once())
            ->method('getTaxValueFromPrice')
            ->willReturn(16);

        $taxCalculation = $this->createTaxCalculation($priceCalculationMock);
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::TAX_RATE, self::GRAND_TOTAL);
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(16, $quoteTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateWhenRateIsFixed()
    {
        $priceCalculationMock = $this->createPriceCalculationMock();

        $priceCalculationMock
            ->expects($this->once())
            ->method('getTaxValueFromPrice')
            ->willReturn(16);

        $taxCalculation = $this->createTaxCalculation($priceCalculationMock);
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::TAX_RATE, self::GRAND_TOTAL);
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(self::TAX_RATE, $quoteTransfer->getTotals()->getTaxTotal()->getTaxRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateWhenRateIsVariedShouldReturnAverage()
    {
        $priceCalculationMock = $this->createPriceCalculationMock();

        $priceCalculationMock
            ->expects($this->once())
            ->method('getTaxValueFromPrice');

        $taxCalculation = $this->createTaxCalculation($priceCalculationMock);
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::TAX_RATE, self::GRAND_TOTAL);
        $quoteTransfer->getItems()[0]->setTaxRate(7);
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(15, $quoteTransfer->getTotals()->getTaxTotal()->getTaxRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateWhenRateIsNotSetShouldReturnEmptyRate()
    {
        $priceCalculationMock = $this->createPriceCalculationMock();

        $taxCalculation = $this->createTaxCalculation($priceCalculationMock);
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals($this->createTotalsTransfer()->setGrandTotal(100));
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(0, $quoteTransfer->getTotals()->getTaxTotal()->getTaxRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateWhenRateIsNotSetShouldReturnEmptyTaxAmount()
    {
        $priceCalculationMock = $this->createPriceCalculationMock();

        $taxCalculation = $this->createTaxCalculation($priceCalculationMock);
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals($this->createTotalsTransfer()->setGrandTotal(100));
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(0, $quoteTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @param int $taxRate
     * @param int $grandTotal
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($taxRate, $grandTotal)
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setTaxRate($taxRate);

        $productOptionTransfer = $this->createProductOptionTransfer();
        $productOptionTransfer->setTaxRate($taxRate);

        $itemTransfer->addProductOption($productOptionTransfer);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->setTaxRate($taxRate);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = $this->createTotalsTransfer();
        $totalsTransfer->setGrandTotal($grandTotal);

        $quoteTransfer->setTotals($totalsTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationMock
     *
     * @return \Spryker\Zed\Tax\Business\Model\TaxCalculation
     */
    protected function createTaxCalculation(PriceCalculationHelperInterface $priceCalculationMock)
    {
        return new TaxCalculation($priceCalculationMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected function createPriceCalculationMock()
    {
        $priceCalculationMock = $this->getMockBuilder(PriceCalculationHelperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $priceCalculationMock;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

}
