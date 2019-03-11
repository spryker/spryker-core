<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use Generated\Shared\Transfer\RounderTransfer;

/**
 * @method \Spryker\Service\UtilNumber\UtilNumberServiceFactory getFactory()
 */
interface UtilNumberServiceInterface
{
    /**
     * Specification:
     * - makes rounding operation with data and options provided in $rounderTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return float
     */
    public function round(RounderTransfer $rounderTransfer): float;

    /**
     * Specification:
     * - makes rounding operation with data and options provided in $rounderTransfer.
     * - cast result value to integer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return int
     */
    public function roundToInt(RounderTransfer $rounderTransfer): int;
}
