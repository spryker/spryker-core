<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

class AccruedTaxCalculator implements AccruedTaxCalculatorInterface
{
    public const DEFAULT_BUCKET_NAME = 'default';

    /**
     * @var \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    protected $priceCalculationHelper;

    /**
     * @var array|float[]
     */
    protected static $roundingErrorBucket = [
         self::DEFAULT_BUCKET_NAME => 0,
    ];

    /**
     * @param \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface $priceCalculationHelper
     */
    public function __construct(PriceCalculationHelperInterface $priceCalculationHelper)
    {
        $this->priceCalculationHelper = $priceCalculationHelper;
    }

    /**
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxRate
     * @param bool $round
     * @param string|null $identifier
     *
     * @return int
     */
    public function getTaxValueFromPrice($price, $taxRate, $round = false, $identifier = null)
    {
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromPrice($price, $taxRate, false);

        $taxAmount += $this->getRoundingErrorDelta($identifier);

        $taxAmountRounded = (int)round($taxAmount);
        $this->setRoundingErrorDelta($taxAmount - $taxAmountRounded, $identifier);

        return $taxAmountRounded;
    }

    /**
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param int $taxRate
     * @param string|null $identifier
     *
     * @return int
     */
    public function getTaxValueFromNetPrice($price, $taxRate, $identifier = null)
    {
        $taxAmount = $this->priceCalculationHelper->getTaxValueFromNetPrice($price, $taxRate, false);

        $taxAmount += $this->getRoundingErrorDelta($identifier);

        $taxAmountRounded = (int)round($taxAmount);
        $this->setRoundingErrorDelta($taxAmount - $taxAmountRounded, $identifier);

        return $taxAmountRounded;
    }

    /**
     * @param float $roundingErrorDelta
     * @param string|null $identifier
     *
     * @return void
     */
    protected function setRoundingErrorDelta($roundingErrorDelta, $identifier = null)
    {
        if ($identifier) {
            static::$roundingErrorBucket[$identifier] = $roundingErrorDelta;
        } else {
            static::$roundingErrorBucket[static::DEFAULT_BUCKET_NAME] = $roundingErrorDelta;
        }
    }

    /**
     * @param string $identifier
     *
     * @return float
     */
    public function getRoundingErrorDelta($identifier)
    {
        if (isset(static::$roundingErrorBucket[$identifier])) {
            $roundingError = static::$roundingErrorBucket[$identifier];
            static::$roundingErrorBucket[$identifier] = 0;

            return $roundingError;
        }

        return static::$roundingErrorBucket[static::DEFAULT_BUCKET_NAME];
    }

    /**
     * @param string|null $identifier
     *
     * @return void
     */
    public function resetRoundingErrorDelta($identifier = null)
    {
        static::$roundingErrorBucket = [];
        static::$roundingErrorBucket[static::DEFAULT_BUCKET_NAME] = 0;
    }
}
