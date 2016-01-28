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
        $this->assertTaxCalculationRequirements($quoteTransfer);

        $taxRates = $this->getTaxesFromCurrentQuoteTransfer($quoteTransfer);
        $effectiveTaxRate = $this->getCalculatedEffectiveTaxRate($taxRates);

        if ($effectiveTaxRate <= 0) {
            $this->setEmptyTaxRateTransfer($quoteTransfer);
            return;
        }

        $grossPrice = $this->getAmountForTaxCalculation($quoteTransfer);
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($grossPrice, $effectiveTaxRate);

        $this->setTaxTotals($quoteTransfer, $effectiveTaxRate, $taxAmount);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array|int[]
     */
    protected function aggregateItemTaxes(QuoteTransfer $quoteTransfer)
    {
        $taxRates = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!empty($itemTransfer->getTaxRate())) {
                $taxRates[] = $itemTransfer->getTaxRate();
            }
            $taxRates = array_merge($taxRates, $this->aggregateProductOptionTaxes($itemTransfer));
        }

        return $taxRates;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array|int[]
     */
    protected function aggregateExpenseTaxes(QuoteTransfer $quoteTransfer)
    {
        $taxRates = [];
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if (empty($expenseTransfer->getTaxRate())) {
                continue;
            }
            $taxRates[] = $expenseTransfer->getTaxRate();
        }
        return $taxRates;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return array|int[]
     */
    protected function aggregateProductOptionTaxes(ItemTransfer $itemTransfer)
    {
        $taxRates = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if (empty($productOptionTransfer->getTaxRate())) {
                continue;
            }
            $taxRates[] = $productOptionTransfer->getTaxRate();
        }
        return $taxRates;
    }

    /**
     * @param array|int[] $taxRates
     * @return float
     */
    protected function getCalculatedEffectiveTaxRate(array $taxRates)
    {
        $totalTaxRate = 0;
        foreach ($taxRates as $taxRate) {
            $totalTaxRate += $taxRate;
        }

        if ($totalTaxRate <= 0) {
            return 0;
        }

        $effectiveTaxRate = $totalTaxRate / count($taxRates);

        return $effectiveTaxRate;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getAmountForTaxCalculation(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param int $effectiveTaxRate
     * @param int $taxAmount
     *
     * @return void
     */
    protected function setTaxTotals(QuoteTransfer $quoteTransfer, $effectiveTaxRate, $taxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setTaxRate($effectiveTaxRate);
        $taxTotalTransfer->setAmount($taxAmount);

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setEmptyTaxRateTransfer(QuoteTransfer $quoteTransfer)
    {
        $taxTotalsTransfer = new TaxTotalTransfer();
        $taxTotalsTransfer->setTaxRate(0);
        $taxTotalsTransfer->setAmount(0);

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalsTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array|int[]
     */
    protected function getTaxesFromCurrentQuoteTransfer(QuoteTransfer $quoteTransfer)
    {
        $taxRates = $this->aggregateItemTaxes($quoteTransfer);
        $taxRates = array_merge($taxRates, $this->aggregateExpenseTaxes($quoteTransfer));

        return $taxRates;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     */
    protected function assertTaxCalculationRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();
        $quoteTransfer->getTotals()->requireGrandTotal();
    }

}
