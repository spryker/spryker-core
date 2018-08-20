<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;

class HardThresholdStrategy extends AbstractMinimumOrderValueStrategy
{
    protected const STRATEGY_KEY = 'hard-threshold';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::STRATEGY_KEY;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return static::GROUP_HARD;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool
    {
        if ($minimumOrderValueThresholdTransfer->getValue() < 1 || $minimumOrderValueThresholdTransfer->getFee()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return int|null
     */
    public function calculateFee(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): ?int
    {
        return null;
    }
}
