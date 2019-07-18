<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

interface PriceCalculationHelperInterface
{
    /**
     * Get the tax value from a given gross price and given tax percentage
     * in rounded integer representation.
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     * @param bool $round
     *
     * @return int|float
     */
    public function getTaxValueFromPrice($price, $taxPercentage, $round = true);

    /**
     * Get the net value from a given gross price and given tax percentage
     * in rounded integer representation.
     *
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxPercentage Tax percentage as float (e. g. 19.6)
     * @param bool $round
     *
     * @return int
     */
    public function getNetValueFromPrice($price, $taxPercentage, $round = true);

    /**
     * Get the real tax rate from a given gross price and given tax amount.
     *
     * @param int $price Price as integer (e.g. 15508 for 155.08)
     * @param float $taxAmount Tax amount (e.g. 19.6)
     *
     * @return float
     */
    public function getTaxRateFromPrice($price, $taxAmount);

    /**
     * @param int $netPrice
     * @param float $taxPercentage
     * @param bool $round
     *
     * @return float|int
     */
    public function getTaxValueFromNetPrice($netPrice, $taxPercentage, bool $round = true);
}
