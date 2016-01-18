<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

class ItemTax
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
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->addItemTaxAmounts($orderTransfer->getItems());
        $this->addExpenseTaxAmounts($orderTransfer->getExpenses());

        $totalTaxAmount = $this->getTotalTaxAmount($orderTransfer);
        $orderEffectiveTaxRate = $this->getOrderEffectiveTaxRate($orderTransfer);

        $totalsTransfer = $orderTransfer->getTotals();

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);
        $taxTotalTransfer->setTaxRate($orderEffectiveTaxRate);

        $totalsTransfer->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getTotalTaxAmount(OrderTransfer $orderTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmountWithProductOptionAndDiscountAmounts();
        }

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }
    
    /**
     * @param \ArrayObject|ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function addExpenseTaxAmounts(\ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            $taxRate = $expenseTransfer->getTaxRate();
            if (empty($taxRate)) {
                continue;
            }

            $expenseTransfer->setUnitTaxAmount(
                $this->priceCalculationHelper->getTaxValueFromPrice($expenseTransfer->getUnitGrossPrice(), $taxRate)
            );

            $expenseTransfer->setSumTaxAmount(
                $this->priceCalculationHelper->getTaxValueFromPrice($expenseTransfer->getSumGrossPrice(), $taxRate)
            );
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getItemEffectiveTaxRate(ItemTransfer $itemTransfer)
    {
        $numberOfItemsWithOption = count($itemTransfer->getProductOptions()) + 1;
        $productOptionEffectiveTaxRate = $this->getProductOptionTotalTaxRate($itemTransfer);

        $totalTaxRate = $productOptionEffectiveTaxRate + $itemTransfer->getTaxRate();

        if (!empty($totalTaxRate)) {
            return $totalTaxRate / $numberOfItemsWithOption;
        }

        return 0;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getProductOptionTotalTaxRate(ItemTransfer $itemTransfer)
    {
        $productOptionEffectiveTaxRate = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionEffectiveTaxRate += $productOptionTransfer->getTaxRate();
        }
        return $productOptionEffectiveTaxRate;
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $items
     *
     * @return void
     */
    protected function addItemTaxAmounts(\ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $effectiveTaxRate = $this->getItemEffectiveTaxRate($itemTransfer);
            if (empty($effectiveTaxRate)) {
                continue;
            }

            $itemUnitTaxAmount = $this->priceCalculationHelper->getTaxValueFromPrice(
                $itemTransfer->getUnitGrossPriceWithProductOptionAndDiscountAmounts(), $effectiveTaxRate
            );

            $itemSumTaxAmount = $this->priceCalculationHelper->getTaxValueFromPrice(
                $itemTransfer->getSumGrossPriceWithProductOptionAndDiscountAmounts(), $effectiveTaxRate
            );

            $itemTransfer->setUnitTaxAmountWithProductOptionAndDiscountAmounts($itemUnitTaxAmount);
            $itemTransfer->setSumTaxAmountWithProductOptionAndDiscountAmounts($itemSumTaxAmount);
        }
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getOrderEffectiveTaxRate(OrderTransfer $orderTransfer)
    {
        $taxRates = $this->getItemWithProductOptionsEffectiveTaxRates($orderTransfer);
        $taxRates = array_merge($this->getExpenseEffectiveTaxRates($orderTransfer), $taxRates);

        $totalTaxRate = array_sum($taxRates);
        if (empty($totalTaxRate)) {
            return 0;
        }

        $effectiveTaxRate = $totalTaxRate / count($taxRates);

        return $effectiveTaxRate;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return array|int[]
     */
    protected function getItemWithProductOptionsEffectiveTaxRates(OrderTransfer $orderTransfer)
    {
        $taxRates = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!empty($itemTransfer->getTaxRate())) {
                $taxRates[] = $itemTransfer->getTaxRate();
            }

            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                if (empty($productOptionTransfer->getTaxRate())) {
                    continue;
                }
                $taxRates[] = $productOptionTransfer->getTaxRate();
            }
        }
        return $taxRates;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return array|int[]
     */
    protected function getExpenseEffectiveTaxRates(OrderTransfer $orderTransfer)
    {
        $taxRates = [];
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if (empty($expenseTransfer->getTaxRate())) {
                continue;
            }
            $taxRates[] = $expenseTransfer->getTaxRate();
        }

        return $taxRates;
    }
}
