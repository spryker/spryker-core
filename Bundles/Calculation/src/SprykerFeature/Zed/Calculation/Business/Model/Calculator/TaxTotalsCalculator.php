<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\TaxItemInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Sales\OrderItemOptionInterface;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\TaxItemTransfer;
use Generated\Shared\Transfer\TaxTransfer;
use Generated\Shared\Tax\TaxSetInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelperInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class TaxTotalsCalculator implements TotalsCalculatorPluginInterface
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
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $groupedPrices = $this->sumPriceToPayGroupedByTaxRate($calculableContainer, $calculableItems);
        $taxTransfer = $this->createTaxTransfer($groupedPrices);

        $totalsTransfer->setTax($taxTransfer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return array
     */
    protected function sumPriceToPayGroupedByTaxRate(
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $groupedPrices = [];
        /* @var $item OrderItemTransfer */
        foreach ($calculableItems as $item) {

            $taxItem = $item->getTax();
            if (is_null($taxItem)) {
                $item->setTax(new TaxItemTransfer());
            }
            $this->addTaxInfo($item->getTax(), $groupedPrices);
            $this->addExpensesTaxInfo($item->getExpenses(), $groupedPrices);
            $this->addOptionsTaxInfo($item->getOptions(), $groupedPrices);
        }
        $this->addExpensesTaxInfo($calculableContainer->getCalculableObject()->getExpenses(), $groupedPrices);

        return $groupedPrices;
    }

    /**
     * @param TaxItemInterface $tax
     * @param $groupedPrices
     */
    protected function addTaxInfo(TaxItemInterface $tax, array &$groupedPrices)
    {
        $taxRate = (float) $tax->getPercentage();
        if ($taxRate === 0.0) {
            return;
        }
        $taxRateIndex = number_format($taxRate, 2);

        if (!isset($groupedPrices[$taxRateIndex])) {
            $groupedPrices[$taxRateIndex] = ['percentage' => $taxRate, 'amount' => 0];
        }
        $groupedPrices[$taxRateIndex]['amount'] += $tax->getAmount();
    }

    /**
     * @param \ArrayObject|ExpenseInterface[] $expenses
     * @param array $groupedPrices
     */
    protected function addExpensesTaxInfo(\ArrayObject $expenses, array &$groupedPrices)
    {
        foreach ($expenses as $expense) {
            $this->addTaxInfo($expense->getTax(), $groupedPrices);
        }
    }

    /**
     * @param \ArrayObject|OrderItemOptionInterface $options
     * @param array $groupedPrices
     */
    protected function addOptionsTaxInfo(\ArrayObject $options, array &$groupedPrices)
    {
        foreach ($options as $option) {
            $this->addTaxInfo($option->getTax(), $groupedPrices);
        }
    }

    /**
     * @param array $groupedPrices
     *
     * @return TaxSetInterface
     */
    protected function createTaxTransfer(array $groupedPrices)
    {
        $tax = new TaxTransfer();
        $totalTax = 0;
        foreach ($groupedPrices as $group) {
            $taxItem = $this->createTaxItemTransfer($group['amount'], $group['percentage']);
            $tax->addTaxRate($taxItem);

            $totalTax += $taxItem->getAmount();
        }
        $tax->setTotalAmount($totalTax);

        return $tax;
    }

    /**
     * @param int $amount
     * @param float $percentage
     *
     * @return TaxItemTransfer
     */
    protected function createTaxItemTransfer($amount, $percentage)
    {
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($amount, $percentage);

        $taxItem = new TaxItemTransfer();
        $taxItem->setPercentage($percentage);
        $taxItem->setAmount($taxAmount);

        return $taxItem;
    }

}
