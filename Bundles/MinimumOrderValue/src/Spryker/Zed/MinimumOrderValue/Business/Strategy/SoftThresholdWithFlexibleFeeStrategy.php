<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;

class SoftThresholdWithFlexibleFeeStrategy extends AbstractMinimumOrderValueStrategy implements MinimumOrderValueStrategyInterface
{
    protected const STRATEGY_KEY = 'soft-threshold-flexible-fee';

    public function __construct()
    {
        $this->setKey(static::STRATEGY_KEY);
        $this->setGroup(static::GROUP_SOFT);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool
    {
        if ($minimumOrderValueThresholdTransfer->getValue() < 1 || $minimumOrderValueThresholdTransfer->getFee() < 1) {
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
        return (int)(($minimumOrderValueThresholdTransfer->getFee() / 100) * $minimumOrderValueThresholdTransfer->getSubTotal());
    }
}
