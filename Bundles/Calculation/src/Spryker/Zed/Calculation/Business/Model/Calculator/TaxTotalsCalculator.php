<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Business\Model\PriceCalculationHelperInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class TaxTotalsCalculator implements TotalsCalculatorPluginInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var TaxSetTransfer[]
     */
    private $calculatedTaxSets = [];

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param ItemTransfer[]|ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $this->calculateTaxAmountsForTaxableItems($calculableContainer, $calculableItems);
        $this->calculateTaxTotals($totalsTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param ItemTransfer[]|ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function calculateTaxAmountsForTaxableItems(CalculableInterface $calculableContainer, $calculableItems)
    {
        foreach ($calculableItems as $item) {
            $this->calculateTax($item);
        }

        /** @var $order CartTransfer|OrderTransfer **/
        $order = $calculableContainer->getCalculableObject();
        $this->calculateTaxForOrderExpenses($order->getExpenses());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|\Generated\Shared\Transfer\ItemTransfer $taxableItem
     *
     * @return void
     */
    private function calculateTax($taxableItem)
    {
        $taxSet = $taxableItem->getTaxSet();
        if ($taxSet === null) {
            return;
        }

        $taxableAmount = $taxableItem->getPriceToPay();

        $taxRates = $taxSet->getTaxRates();

        $effectiveTaxRate = 0;
        foreach ($taxRates as &$taxRate) {
            $effectiveTaxRate += $taxRate->getRate();
        }

        $taxAmountForTaxSet = $this->priceCalculationHelper->getTaxValueFromPrice($taxableAmount, $effectiveTaxRate);

        $taxSet->setAmount($taxAmountForTaxSet)
            ->setEffectiveRate($effectiveTaxRate);

        $this->calculatedTaxSets[] = clone $taxSet;
    }

    /**
     * @param \ArrayObject $expenses
     *
     * @return void
     */
    public function calculateTaxForOrderExpenses(\ArrayObject $expenses)
    {
        foreach ($expenses as $expense) {
            $this->calculateTax($expense);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return void
     */
    public function calculateTaxTotals(TotalsTransfer $totalsTransfer)
    {
        /** @var $groupedTotals TaxSetTransfer[] **/
        $groupedTotals = [];
        foreach ($this->calculatedTaxSets as $taxSet) {
            if (!isset($groupedTotals[$taxSet->getIdTaxSet()])) {
                $groupedTotals[$taxSet->getIdTaxSet()] = $taxSet;
                continue;
            }

            $oldAmount = $groupedTotals[$taxSet->getIdTaxSet()]->getAmount();
            $groupedTotals[$taxSet->getIdTaxSet()]->setAmount($oldAmount + $taxSet->getAmount());
        }

        $taxTotalsTransfer = new TaxTotalTransfer();
        $totalEffectiveRate = 0;
        foreach ($groupedTotals as $taxSet) {
            $taxTotalsTransfer->addTaxSet($taxSet);
            $totalEffectiveRate += $taxSet->getEffectiveRate();
        }

        if (!empty($totalEffectiveRate)) {
            $taxAmountForTaxSet = $this->priceCalculationHelper->getTaxValueFromPrice(
                $totalsTransfer->getGrandTotalWithDiscounts(),
                $totalEffectiveRate
            );
            $taxTotalsTransfer->setAmount($taxAmountForTaxSet);
        }

        $totalsTransfer->setTaxTotal($taxTotalsTransfer);
    }

}
