<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Spryker\Zed\Tax\Business\Model\Exception\CalculationException;

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
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\CalculationException
     *
     * @return int|float
     */
    public function getTaxValueFromPrice($price, $taxPercentage, $round = true)
    {
        $price = (int)$price;

        if ($price < 0) {
            throw new CalculationException('Invalid price value given.');
        }

        $amount = ($price * $taxPercentage) / ($taxPercentage + 100);
        if (!$round) {
            return $amount;
        }

        return (int)round($amount);
    }

    /**
     * Get the net value from a given gross price and given tax percentage
     * in rounded integer representation.
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     * @param bool $round
     *
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\CalculationException
     *
     * @return int|float
     */
    public function getNetValueFromPrice($price, $taxPercentage, $round = true)
    {
        $price = (int)$price;

        if ($price < 0) {
            throw new CalculationException('Invalid price value given.');
        }

        $amount = ($price * 100) / ($taxPercentage + 100);

        if (!$round) {
            return $amount;
        }

        return (int)round($amount);
    }

    /**
     * Get the real tax rate from a given gross price and given tax amount.
     *
     * @param int $price Price as integer (e.g. 15508 for 155.08)
     * @param float $taxAmount Tax amount (e.g. 19.6)
     *
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\CalculationException
     *
     * @return float
     */
    public function getTaxRateFromPrice($price, $taxAmount)
    {
        $price = (int)$price;

        if ($price < 0 || $taxAmount <= 0) {
            throw new CalculationException('Invalid price or tax amount value given.');
        }

        $netPrice = $price - $taxAmount;
        if ($netPrice <= 0) {
            throw new CalculationException('Division by zero.');
        }

        return $taxAmount / $netPrice;
    }

    /**
     * @param int $netPrice
     * @param float $taxPercentage
     * @param bool $round
     *
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\CalculationException
     *
     * @return float|int
     */
    public function getTaxValueFromNetPrice($netPrice, $taxPercentage, bool $round = true)
    {
        $price = (int)$netPrice;

        if ($price < 0) {
            throw new CalculationException('Invalid price value given.');
        }

        $amount = $netPrice * $taxPercentage / 100;

        if (!$round) {
            return $amount;
        }

        return (int)round($amount);
    }
}
