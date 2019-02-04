<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Business\Model;

interface FloatCalculatorInterface
{
    /**
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return int
     */
    public function compare(float $leftOperand, float $rightOperand): int;
}
