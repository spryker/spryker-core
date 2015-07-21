<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\CartInterface;
use Generated\Shared\Calculation\OrderItemInterface;
use Generated\Shared\Calculation\CartItemInterface;
use Generated\Shared\Calculation\TaxSetInterface;
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
     * @var $calculatedTaxSets TaxSetInterface[]
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
     * @param CartItemInterface[]|OrderItemInterface[] $calculableItems
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
     * @param CartItemInterface[]|OrderItemInterface[] $calculableItems
     */
    public function calculateTaxAmountsForTaxableItems(CalculableInterface $calculableContainer, $calculableItems)
    {
        foreach ($calculableItems as $item) {
            $this->calculateTax($item);
            $this->calculateTaxForExpenses($item->getExpenses());
        }

        /** @var $order CartInterface|OrderInterface **/
        $order = $calculableContainer->getCalculableObject();
        $this->calculateTaxForExpenses($order->getExpenses());
    }

    /**
     * @param OrderItemInterface|CartItemInterface $taxableItem
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

        $this->calculatedTaxSets[] = clone $taxSet;
    }

    /**
     * @param \ArrayObject $expenses
     */
    public function calculateTaxForExpenses(\ArrayObject $expenses)
    {
        foreach ($expenses as $expense) {
            $this->calculateTax($expense);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     */
    public function calculateTaxTotals(TotalsInterface $totalsTransfer)
    {
        /** @var $groupedTotals TaxSetInterface[] **/
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
