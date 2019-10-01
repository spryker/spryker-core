<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Tax\Business\Model;

interface AccruedTaxCalculatorInterface
{
    /**
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxRate
     * @param bool $round
     * @param string|null $identifier
     *
     * @return int
     */
    public function getTaxValueFromPrice($price, $taxRate, $round = false, $identifier = null);

    /**
     * @param int $price Price as integer (e. g 15508 for 155.08)
     * @param float $taxRate
     * @param string|null $identifier
     *
     * @return int
     */
    public function getTaxValueFromNetPrice($price, $taxRate, $identifier = null);

    /**
     * @return void
     */
    public function resetRoundingErrorDelta();

    /**
     * @param string $identifier
     *
     * @return float
     */
    public function getRoundingErrorDelta($identifier);
}
