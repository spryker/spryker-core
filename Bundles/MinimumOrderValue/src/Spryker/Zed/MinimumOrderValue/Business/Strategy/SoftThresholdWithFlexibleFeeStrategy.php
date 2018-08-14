<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;

class SoftThresholdWithFlexibleFeeStrategy extends AbstractMinimumOrderValueStrategy implements MinimumOrderValueStrategyInterface
{
    protected const STRATEGY_KEY = 'soft-threshold-flexible-fee';

    public function __construct()
    {
        $this->setKey(static::STRATEGY_KEY);
        $this->setGroup(static::GROUP_SOFT);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueTransfer $minimumOrderValueTransfer): bool
    {
        if ($minimumOrderValueTransfer->getValue() < 1 || $minimumOrderValueTransfer->getFee() < 1) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return int|null
     */
    public function calculateFee(MinimumOrderValueTransfer $minimumOrderValueTransfer): ?int
    {
        return (int)(($minimumOrderValueTransfer->getFee() / 100) * $minimumOrderValueTransfer->getSubTotal());
    }
}
