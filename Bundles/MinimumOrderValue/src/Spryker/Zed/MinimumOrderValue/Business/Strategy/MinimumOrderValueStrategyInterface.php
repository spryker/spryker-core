<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

interface MinimumOrderValueStrategyInterface
{
    public const GROUP_HARD = 'Hard';
    public const GROUP_SOFT = 'Soft';

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return string
     */
    public function getGroup(): string;

    /**
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function toTransfer(): MinimumOrderValueTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isValid(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isApplicable(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return int|null
     */
    public function calculateFee(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): ?int;
}
