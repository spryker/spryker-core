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
     * @var \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var int
     */
    protected $roundingError = 0;

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $unitTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getUnitGrossPrice(),
                $expenseTransfer->getTaxRate()
            );

            $sumTaxAmount = $this->calculateTaxAmount(
                $expenseTransfer->getSumGrossPrice(),
                $expenseTransfer->getTaxRate()
            );

            $expenseTransfer->setUnitTaxAmount($unitTaxAmount);
            $expenseTransfer->setSumTaxAmount($sumTaxAmount);
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
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($price, $taxRate, false);

        $taxAmount += $this->roundingError;

        $taxAmountRounded = round($taxAmount, 4);
        $this->roundingError = $taxAmount - $taxAmountRounded;

        return $taxAmountRounded;
    }
}
