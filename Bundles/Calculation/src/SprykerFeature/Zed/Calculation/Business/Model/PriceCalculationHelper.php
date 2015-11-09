<?php

namespace SprykerFeature\Zed\Calculation\Business\Model;

class PriceCalculationHelper implements PriceCalculationHelperInterface
{

    /**
     * Get the tax value from a given gross price and given tax percentage
     * in rounded integer representation.
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     * @param bool $round
     *
     * @return int
     */
    public function getTaxValueFromPrice($price, $taxPercentage, $round = true)
    {
        $price = (int) $price;
        $amount = ($price * $taxPercentage) / ($taxPercentage + 100);
        if (!$round) {
            return $amount;
        }

        return round($amount);
    }

    /**
     * Get the net value from a given gross price and given tax percentage
     * in rounded integer representation.
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     *
     * @return int
     */
    public function getNetValueFromPrice($price, $taxPercentage)
    {
        $price = (int) $price;
        $amount = ($price * 100) / ($taxPercentage + 100);

        return round($amount);
    }

    /**
     * Get the real tax rate from a given gross price and given tax amount.
     *
     * @param int $price Price as integer (e.g. 15508 for 155.08)
     * @param int $taxAmount Tax amount (e.g. 196)
     *
     * @return float
     */
    public function getTaxRateFromPrice($price, $taxAmount)
    {
        $price = (int) $price;
        $taxAmount = (int) $taxAmount;

        if ($taxAmount === 0) {
            return 0;
        }

        return $taxAmount / ($price - $taxAmount);
    }

}
