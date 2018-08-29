<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

interface MinimumOrderValueStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns strategy key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Specification:
     * - Returns strategy group.
     *
     * @api
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Specification:
     * - Checks is threshold transfer valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool;

    /**
     * Specification:
     * - Checks is threshold transfer applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isApplicable(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool;

    /**
     * Specification:
     * - Calculates fee for threshold.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return int|null
     */
    public function calculateFee(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): ?int;

    /**
     * Specification:
     * - Creates MinimumOrderValueTypeTransfer from strategy.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function toTransfer(): MinimumOrderValueTypeTransfer;
}
