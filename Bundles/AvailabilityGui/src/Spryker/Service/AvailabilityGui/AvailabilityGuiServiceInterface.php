<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui;

interface AvailabilityGuiServiceInterface
{
    /**
     * Specification:
     *  - compares two float numbers with precision defined in config.
     *
     * @api
     *
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return int
     */
    public function isEqual(float $leftOperand, float $rightOperand): int;
}
