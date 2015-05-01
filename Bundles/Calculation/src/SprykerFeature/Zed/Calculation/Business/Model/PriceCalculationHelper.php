<?php

namespace SprykerFeature\Zed\Calculation\Business\Model;

/**
 * Class PriceCalculationHelper
 * @package SprykerFeature\Zed\Calculation\Business\Model
 */
class PriceCalculationHelper implements
    PriceCalculationHelperInterface
{
    /**
     * Get the tax value from a given gross price and given tax percentage
     * in rounded integer representation
     * @param int     $priceInCent   Price as integer (e. g 15508 for 155.08)
     * @param float   $taxPercentage Tax percentage as float (e. g. 19.6)
     * @param boolean $round
     * @return int
     */
    public function getTaxValueFromPrice($priceInCent, $taxPercentage, $round = true)
    {
        $priceInCent = (int) $priceInCent;
        $taxPercentage = (float) $taxPercentage;
        $amount = (($priceInCent * $taxPercentage) / ($taxPercentage + 100));
        if (!$round) {
            return $amount;
        }

        return round($amount);
    }

    /**
     * Get the net value from a given gross price and given tax percentage
     * in rounded integer representation
     * @param int   $priceInCent   $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     * @return int
     */
    public function getNetValueFromPrice($priceInCent, $taxPercentage)
    {
        $priceInCent = (int) $priceInCent;
        $taxPercentage = (float) $taxPercentage;
        $amount = (($priceInCent * 100) / ($taxPercentage + 100));

        return round($amount);
    }

    /**
     * Get the real tax rate from a given gross price and given tax amount
     * in rounded integer representation
     * @param int $priceInCent     $price Price as integer (e. g 15508 for 155.08)
     * @param int $taxAmountInCent Tax amount in cents (e. g. 196)
     * @return float
     */
    public function getTaxRateFromPrice($priceInCent, $taxAmountInCent)
    {
        $priceInCent = (int) $priceInCent;
        $taxAmountInCent = (int) $taxAmountInCent;
        if ($taxAmountInCent == 0) {
            return 0;
        }

        return ($taxAmountInCent / ($priceInCent - $taxAmountInCent));
    }
}
