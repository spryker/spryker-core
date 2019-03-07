<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui\Model;

interface FloatCalculatorInterface
{
    /**
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return bool
     */
    public function isEqual(float $leftOperand, float $rightOperand): bool;
}
