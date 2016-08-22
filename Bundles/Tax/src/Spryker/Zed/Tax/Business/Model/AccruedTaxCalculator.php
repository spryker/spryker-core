<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

class AccruedTaxCalculator implements AccruedTaxCalculatorInterface
{

    /**
     * @var \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var float
     */
    protected static $roundingErrorDelta = 0.0;

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param int $taxRate
     *
     * @return float
     */
    public function getTaxValueFromPrice($price, $taxRate)
    {
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($price, $taxRate, false);

        $taxAmount += static::$roundingErrorDelta;

        $taxAmountRounded = round($taxAmount, 2);
        static::$roundingErrorDelta = $taxAmount - $taxAmountRounded;

        return $taxAmountRounded;
    }

    /**
     * @return void
     */
    public function resetRoundingErrorDelta()
    {
        static::$roundingErrorDelta = 0.0;
    }

}
