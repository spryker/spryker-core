<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class ItemTax implements CalculatorInterface
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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $unitTaxAmount = $this->calculateTaxAmount(
                $itemTransfer->getUnitGrossPrice(),
                $itemTransfer->getTaxRate()
            );

            $sumTaxAmount = $this->calculateTaxAmount(
                $itemTransfer->getSumTaxAmount(),
                $itemTransfer->getTaxRate()
            );

            $itemTransfer->setUnitTaxAmount($unitTaxAmount);
            $itemTransfer->setSumTaxAmount($sumTaxAmount);
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
