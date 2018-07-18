<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategies\Strategy;

use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueAbstractStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;

class HardThresholdStrategy extends MinimumOrderValueAbstractStrategy implements MinimumOrderValueStrategyInterface
{
    protected const STRATEGY_KEY = 'hard-threshold';

    public function __construct()
    {
        $this->setKey(static::STRATEGY_KEY);
        $this->setGroup(static::GROUP_HARD);
    }

    /**
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return bool
     */
    public function validate(int $thresholdValue, ?int $fee = null): bool
    {
        if ($thresholdValue < 1) {
            return false;
        }

        return true;
    }
}
