<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Calculation\Transfer\TaxItem;
use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelperInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxableItemInterface;
use SprykerEngine\Zed\Kernel\Locator;

class TaxTotalsCalculator extends AbstractCalculator implements TotalsCalculatorPluginInterface
{
    /**
     * @var PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @param Locator $locator
     * @param PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(Locator $locator, PriceCalculationHelperInterface $priceCalculationHelper)
    {
        parent::__construct($locator);
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $groupedPrices = $this->sumPriceToPayGroupedByTaxRate($calculableContainer, $calculableItems);
        $taxTransfer = $this->createTaxTransfer($groupedPrices);

        $totalsTransfer->setTax($taxTransfer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     *
     * @return array
     */
    protected function sumPriceToPayGroupedByTaxRate(
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $groupedPrices = [];
        foreach ($calculableItems as $item) {
            $this->addTaxInfo($item, $groupedPrices);
            $this->addExpensesTaxInfo($item, $groupedPrices);
            $this->addOptionsTaxInfo($item, $groupedPrices);
        }
        $this->addExpensesTaxInfo($calculableContainer, $groupedPrices);

        return $groupedPrices;
    }

    /**
     * @param TaxableItemInterface $taxableObject
     * @param array $groupedPrices
     */
    protected function addTaxInfo(TaxableItemInterface $taxableObject, array &$groupedPrices)
    {
        $taxRate = (float) $taxableObject->getTaxPercentage();
        if ($taxRate === 0.0) {
            return;
        }
        $taxRateIndex = number_format($taxRate, 2);

        if (!isset($groupedPrices[$taxRateIndex])) {
            $groupedPrices[$taxRateIndex] = ['percentage' => $taxRate, 'amount' => 0];
        }
        $groupedPrices[$taxRateIndex]['amount'] += $taxableObject->getPriceToPay();
    }

    /**
     * @param ExpenseContainerInterface $expenseContainer
     * @param array $groupedPrices
     */
    protected function addExpensesTaxInfo(ExpenseContainerInterface $expenseContainer, array &$groupedPrices)
    {
        foreach ($expenseContainer->getExpenses() as $expense) {
            $this->addTaxInfo($expense, $groupedPrices);
        }
    }

    /**
     * @param CalculableItemInterface $item
     * @param array $groupedPrices
     */
    protected function addOptionsTaxInfo(CalculableItemInterface $item, array &$groupedPrices)
    {
        foreach ($item->getOptions() as $option) {
            $this->addTaxInfo($option, $groupedPrices);
        }
    }

    /**
     * @param array $groupedPrices
     *
     * @return TaxInterface
     */
    protected function createTaxTransfer(array $groupedPrices)
    {
        $tax = new \Generated\Shared\Transfer\CalculationTaxTransfer();
        $totalTax = 0;
        foreach ($groupedPrices as $group) {
            $taxItem = $this->createTaxItem($group['amount'], $group['percentage']);
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
     * @return TaxItem
     */
    protected function createTaxItem($amount, $percentage)
    {
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($amount, $percentage);

        $taxItem = new \Generated\Shared\Transfer\CalculationTaxItemTransfer();
        $taxItem->setPercentage($percentage);
        $taxItem->setAmount($taxAmount);

        return $taxItem;
    }
}
