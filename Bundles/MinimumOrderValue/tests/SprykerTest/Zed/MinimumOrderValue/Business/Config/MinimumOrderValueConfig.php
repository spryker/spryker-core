<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business\Config;

use Spryker\Zed\MinimumOrderValue\Business\Strategy\HardThresholdStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFixedFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFlexibleFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithMessageStrategy;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig as SprykerMinimumOrderValueConfig;

class MinimumOrderValueConfig extends SprykerMinimumOrderValueConfig
{
    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface[]
     */
    public function getMinimumOrderValueStrategies(): array
    {
        return [
            new HardThresholdStrategy(),
            new SoftThresholdWithMessageStrategy(),
            new SoftThresholdWithFixedFeeStrategy(),
            new SoftThresholdWithFlexibleFeeStrategy(),
        ];
    }
}
