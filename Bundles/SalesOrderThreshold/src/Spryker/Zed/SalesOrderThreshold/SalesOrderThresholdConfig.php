<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold;

use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig as SharedSalesOrderThresholdConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderThresholdConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines list of applicable threshold strategies.
     *
     * @api
     *
     * @return array<string>
     */
    public function getApplicableThresholdStrategies(): array
    {
        return [
            SharedSalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD,
            SharedSalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM,
        ];
    }
}
