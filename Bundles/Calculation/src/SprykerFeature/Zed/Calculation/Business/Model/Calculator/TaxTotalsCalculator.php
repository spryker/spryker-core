<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Sales\OrderItemOptionInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Tax\TaxSetInterface;
use Generated\Shared\Transfer\TaxTotalTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelperInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class TaxTotalsCalculator implements TotalsCalculatorPluginInterface
{

    /**
     * @var $priceCalculationHelper PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var $calculatedTaxSets TaxSetTransfer[]
     */
    private $calculatedTaxSets;

    /**
     * @param PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $this->calculateTaxAmountsForTaxableItems($calculableContainer, $calculableItems);
        $this->calculateTaxTotals($totalsTransfer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function calculateTaxAmountsForTaxableItems(CalculableInterface $calculableContainer, $calculableItems)
    {
        /** @var $product OrderItemTransfer **/
        foreach ($calculableItems as $product) {
            $this->calculateTax($product);
            foreach ($product->getExpenses() as $expense) {
                $this->calculateTax($expense);
            }
        }

        /** @var $order OrderTransfer **/
        $order = $calculableContainer->getCalculableObject();
        $globalExpenses = $order->getExpenses();
        foreach ($globalExpenses as $globalExpense) {
            $this->calculateTax($globalExpense);
        }
    }

    /**
     * @param OrderItemTransfer $taxableItem
     */
    private function calculateTax($taxableItem)
    {
        $taxSet = $taxableItem->getTaxSet();
        if (null === $taxSet) {
            return;
        }

        $taxableAmount = $taxableItem->getPriceToPay();

        $taxRates = $taxSet->getTaxRates();

        $effectiveTaxRate = 0;
        foreach ($taxRates as &$taxRate) {
            $effectiveTaxRate += $taxRate->getRate();
        }

        $taxAmountForTaxSet = $this->priceCalculationHelper->getTaxValueFromPrice($taxableAmount, $effectiveTaxRate);
        $taxSet->setAmount($taxAmountForTaxSet);

        $this->calculatedTaxSets[] = $taxSet;
    }


    public function calculateTaxTotals(TotalsInterface $totalsTransfer)
    {
        /** @var $groupedTotals TaxSetTransfer[] **/
        $groupedTotals = [];

        foreach ($this->calculatedTaxSets as $taxSet) {

            if (false == isset($groupedTotals[$taxSet->getIdTaxSet()])) {
                $groupedTotals[$taxSet->getIdTaxSet()] = $taxSet;
                continue;
            }

            $oldAmount = $groupedTotals[$taxSet->getIdTaxSet()]->getAmount();
            $groupedTotals[$taxSet->getIdTaxSet()]->setAmount($oldAmount + $taxSet->getAmount());
        }

        $taxTotalsTransfer = new TaxTotalTransfer();
        foreach ($groupedTotals as $taxSet) {
            $taxTotalsTransfer->addTaxSet($taxSet);
        }

        $totalsTransfer->setTaxTotal($taxTotalsTransfer);
    }
}
