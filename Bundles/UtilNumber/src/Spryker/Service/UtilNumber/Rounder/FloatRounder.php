<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Rounder;

use Generated\Shared\Transfer\RounderTransfer;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return float
     */
    public function round(RounderTransfer $rounderTransfer): float
    {
        $rounderTransfer->requireValue()
            ->requirePrecision()
            ->requireRoundMode();

        return round(
            $rounderTransfer->getValue(),
            $rounderTransfer->getPrecision(),
            $rounderTransfer->getRoundMode()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RounderTransfer $rounderTransfer
     *
     * @return int
     */
    public function roundToInt(RounderTransfer $rounderTransfer): int
    {
        return (int)$this->round($rounderTransfer);
    }
}
