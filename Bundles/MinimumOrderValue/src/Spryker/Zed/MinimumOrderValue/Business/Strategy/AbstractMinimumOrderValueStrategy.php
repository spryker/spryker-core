<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

abstract class AbstractMinimumOrderValueStrategy implements MinimumOrderValueStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isApplicable(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool
    {
        return $minimumOrderValueThresholdTransfer->getSubTotal() < $minimumOrderValueThresholdTransfer->getValue();
    }

    /**
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function toTransfer(): MinimumOrderValueTypeTransfer
    {
        return (new MinimumOrderValueTypeTransfer())
            ->setKey($this->getKey())
            ->setThresholdGroup($this->getGroup());
    }
}
