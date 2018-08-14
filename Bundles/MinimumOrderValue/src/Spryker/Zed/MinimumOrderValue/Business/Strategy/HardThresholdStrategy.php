<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;

class HardThresholdStrategy extends AbstractMinimumOrderValueStrategy implements MinimumOrderValueStrategyInterface
{
    protected const STRATEGY_KEY = 'hard-threshold';

    public function __construct()
    {
        $this->setKey(static::STRATEGY_KEY);
        $this->setGroup(static::GROUP_HARD);
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueTransfer $minimumOrderValueTransfer): bool
    {
        if ($minimumOrderValueTransfer->getValue() < 1 || $minimumOrderValueTransfer->getFee()) {
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
        return null;
    }
}
