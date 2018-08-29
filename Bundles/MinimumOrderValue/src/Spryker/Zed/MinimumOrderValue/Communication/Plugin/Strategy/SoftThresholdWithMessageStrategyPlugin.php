<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;

class SoftThresholdWithMessageStrategyPlugin extends AbstractMinimumOrderValueStrategyPlugin
{
    /**
     * {inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return MinimumOrderValueConfig::THRESHOLD_STRATEGY_KEY_SOFT;
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
        return MinimumOrderValueConfig::GROUP_SOFT;
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
        return !($minimumOrderValueThresholdTransfer->getThreshold() < 1 || $minimumOrderValueThresholdTransfer->getFee());
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
        return null;
    }
}
