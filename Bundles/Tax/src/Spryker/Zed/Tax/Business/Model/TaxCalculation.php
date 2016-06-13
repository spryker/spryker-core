<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;

class TaxCalculation implements CalculatorInterface
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
        $totalTaxAmount = 0;
        $totalTaxAmount += $this->sumItemTaxes($quoteTransfer);
        $totalTaxAmount += $this->sumExpenseTaxes($quoteTransfer);

        $this->setTaxTotals($quoteTransfer, $totalTaxAmount);

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $taxAmount
     *
     * @return void
     */
    protected function setTaxTotals(QuoteTransfer $quoteTransfer, $taxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount(round($taxAmount));

        $quoteTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function sumExpenseTaxes(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($quoteTransfer->getItems() as $expenseTransfer) {
            $totalTaxAmount += $expenseTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function sumItemTaxes(QuoteTransfer $quoteTransfer)
    {
        $totalTaxAmount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmount();
        }

        return $totalTaxAmount;
    }

}
