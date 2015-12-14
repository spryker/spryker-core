<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Tax\Business\Model\Calculator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Tax\Business\Model\TaxCalculation;
use SprykerFeature\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

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
        $quoteTransfer->setTotals($this->createTotalsTransfer());
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
        $quoteTransfer->setTotals($this->createTotalsTransfer());
        $taxCalculation->recalculate($quoteTransfer);

        $this->assertEquals(0, $quoteTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @param int $taxRate
     * @param int $grandTotal
     *
     * @return QuoteTransfer
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
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @param PriceCalculationHelperInterface $priceCalculationMock
     *
     * @return TaxCalculation
     */
    protected function createTaxCalculation(PriceCalculationHelperInterface $priceCalculationMock)
    {
        return new TaxCalculation($priceCalculationMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PriceCalculationHelperInterface
     */
    protected function createPriceCalculationMock()
    {
        $priceCalculationMock = $this->getMockBuilder('\SprykerFeature\Zed\Tax\Business\Model\PriceCalculationHelperInterface')
            ->disableOriginalConstructor()
            ->getMock();

        return $priceCalculationMock;
    }

    /**
     * @return ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }
}
