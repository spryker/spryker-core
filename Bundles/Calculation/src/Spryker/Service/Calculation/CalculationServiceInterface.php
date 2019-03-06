<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation;

interface CalculationServiceInterface
{
    /**
     * Specification:
     * - converts float value to int after rounding operation with precision and round mode defined in config.
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convert(float $value): int;
}
