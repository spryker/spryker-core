<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Rounder;

use Generated\Shared\Transfer\RounderTransfer;

interface FloatRounderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return float
     */
    public function round(RounderTransfer $rounderTransfer): float;

    /**
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return int
     */
    public function roundToInt(RounderTransfer $rounderTransfer): int;
}
