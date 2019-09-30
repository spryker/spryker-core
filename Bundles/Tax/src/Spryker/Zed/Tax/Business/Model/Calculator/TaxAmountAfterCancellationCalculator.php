<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
use Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface;

class TaxAmountAfterCancellationCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface
     */
    protected $accruedTaxCalculator;

    /**
     * @param \Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface $accruedTaxCalculator
     */
    public function __construct(AccruedTaxCalculatorInterface $accruedTaxCalculator)
    {
        $this->accruedTaxCalculator = $accruedTaxCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateItemTaxAmountAfterCancellation($calculableObjectTransfer);
        $this->calculateOrderExpenseTaxAmountAfterCancellation($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function calculateItemTaxAmountAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getCanceledAmount()) {
                continue;
            }

            $itemTransfer->requireSumPriceToPayAggregation();
            $itemTransfer->requireSumTaxAmountFullAggregation();

            $canceledTaxableAmount = $itemTransfer->getSumPriceToPayAggregation() - $itemTransfer->getCanceledAmount();

            if (!$canceledTaxableAmount) {
                $itemTransfer->setTaxAmountAfterCancellation($itemTransfer->getSumTaxAmountFullAggregation());
            }

            $taxAmount = $this->calculateTaxAmount(
                $canceledTaxableAmount,
                $itemTransfer->getTaxRateAverageAggregation(),
                CalculationPriceMode::PRICE_MODE_GROSS
            );

            $itemTransfer->setTaxAmountAfterCancellation($itemTransfer->getSumTaxAmountFullAggregation() - $taxAmount);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function calculateOrderExpenseTaxAmountAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            if (!$expenseTransfer->getCanceledAmount()) {
                continue;
            }

            $expenseTransfer->requireSumPriceToPayAggregation();
            $expenseTransfer->requireSumTaxAmount();

            $canceledTaxableAmount = $expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getCanceledAmount();

            if (!$canceledTaxableAmount) {
                $expenseTransfer->setTaxAmountAfterCancellation($expenseTransfer->getSumTaxAmount());
            }

            $taxAmount = $this->calculateTaxAmount(
                $canceledTaxableAmount,
                $expenseTransfer->getTaxRate(),
                $calculableObjectTransfer->getPriceMode()
            );

            $expenseTransfer->setTaxAmountAfterCancellation($expenseTransfer->getSumTaxAmount() - $taxAmount);
        }
    }

    /**
     * @param int $price
     * @param float $taxRate
     * @param string $priceMode
     *
     * @return int
     */
    protected function calculateTaxAmount($price, $taxRate, $priceMode = CalculationPriceMode::PRICE_MODE_GROSS)
    {
        if ($priceMode === CalculationPriceMode::PRICE_MODE_NET) {
            return $this->accruedTaxCalculator->getTaxValueFromNetPrice($price, $taxRate);
        }

        return $this->accruedTaxCalculator->getTaxValueFromPrice($price, $taxRate, true);
    }
}
