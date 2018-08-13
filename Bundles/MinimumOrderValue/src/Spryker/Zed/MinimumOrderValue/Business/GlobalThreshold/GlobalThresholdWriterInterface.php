<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;

interface GlobalThresholdWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function setGlobalThreshold(
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer;
}
