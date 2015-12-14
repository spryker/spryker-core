<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;

class TaxCalculation implements CalculatorInterface
{
    /**
     * @var PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var array|float[]
     */
    protected $taxRates = [];

    /**
     * @param PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $this->aggregateItemTaxes($quoteTransfer);
        $this->aggregateExpenseTaxes($quoteTransfer);

        $effectiveTaxRate = $this->getCalculatedEffectiveTaxRate();

        if ($effectiveTaxRate <= 0) {
            $taxTotalTransfer = $this->createTaxTotalTransfer(0, 0);
            $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
            return;
        }

        $grossPrice = $this->getAmountForTaxCalculation($quoteTransfer);
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($grossPrice, $effectiveTaxRate);

        $taxTotalTransfer = $this->createTaxTotalTransfer($effectiveTaxRate, $taxAmount);

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function aggregateItemTaxes(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->setTaxRate($itemTransfer->getTaxRate());
            $this->aggregateProductOptionTaxes($itemTransfer);
        }
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function aggregateExpenseTaxes(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $this->setTaxRate($expenseTransfer->getTaxRate());
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function aggregateProductOptionTaxes(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->setTaxRate($productOptionTransfer->getTaxRate());
        }
    }

    /**
     * @return float
     */
    protected function getCalculatedEffectiveTaxRate()
    {
        $totalTaxRate = 0;
        foreach ($this->taxRates as $taxRate) {
            $totalTaxRate += $taxRate;
        }

        if ($totalTaxRate <= 0) {
            return 0;
        }

        $effectiveTaxRate = $totalTaxRate / count($this->taxRates);

        return $effectiveTaxRate;
    }

    /**
     * @param float $taxRate
     *
     * @return void
     */
    protected function setTaxRate($taxRate)
    {
        $this->taxRates[] = $taxRate;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getAmountForTaxCalculation(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getTotals()->requireGrandTotal();

        return $quoteTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @param int $effectiveTaxRate
     * @param int $taxAmount
     *
     * @return TaxTotalTransfer
     */
    protected function createTaxTotalTransfer($effectiveTaxRate, $taxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();

        $taxTotalTransfer->setTaxRate($effectiveTaxRate);
        $taxTotalTransfer->setAmount($taxAmount);

        return $taxTotalTransfer;
    }

}
