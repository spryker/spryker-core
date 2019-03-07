<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

interface DiscountServiceInterface
{
    /**
     * Specification:
     * - rounds float value with precision and round mode defined in config.
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;

    /**
     * Specification:
     * - rounds float value with precision and round mode defined in config.
     * - converts given result to integer.
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function roundToInt(float $value): int;
}
