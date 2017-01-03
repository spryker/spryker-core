<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class ExpenseTaxCalculator implements CalculatorInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->accruedTaxCalculator->resetRoundingErrorDelta();
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $unitTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getUnitGrossPrice(),
                $expenseTransfer->getTaxRate()
            );

            $sumTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getSumGrossPrice(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setUnitTaxAmount((int)round($unitTaxAmount));
            $expenseTransfer->setSumTaxAmount((int)round($sumTaxAmount));

            $expenseTransfer->setUnitTaxTotal((int)round($unitTaxAmount));
            $expenseTransfer->setSumTaxTotal((int)round($sumTaxAmount));
        }
    }

    /**
     * @param int $price
     * @param float $taxRate
     *
     * @return float
     */
    protected function calculateTaxAmount($price, $taxRate)
    {
        return $this->accruedTaxCalculator->getTaxValueFromPrice($price, $taxRate);
    }

}
