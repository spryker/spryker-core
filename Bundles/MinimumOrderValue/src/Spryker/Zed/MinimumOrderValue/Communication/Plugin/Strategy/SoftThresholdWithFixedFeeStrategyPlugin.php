<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;

class SoftThresholdWithFixedFeeStrategyPlugin extends AbstractMinimumOrderValueStrategyPlugin
{
    protected const STRATEGY_KEY = 'soft-threshold-fixed-fee';

    /**
     * {inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::STRATEGY_KEY;
    }

    /**
     * {inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getGroup(): string
    {
        return static::GROUP_SOFT;
    }

    /**
     * {inheritdoc}
     *
     * @api
     *
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
     * {inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return int|null
     */
    public function calculateFee(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): ?int
    {
        return $minimumOrderValueThresholdTransfer->getFee();
    }
}
