<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Calculator;

interface PrecisionCalculatorInterface
{
    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return int
     */
    public function getMaxPrecision(float $firstQuantity, float $secondQuantity): int;
}
