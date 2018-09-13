<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantRelationshipSalesOrderThresholdGui;

interface MerchantRelationshipSalesOrderThresholdGuiConfig
{
    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD
     */
    public const GROUP_HARD = 'Hard';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_SOFT
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';
}
