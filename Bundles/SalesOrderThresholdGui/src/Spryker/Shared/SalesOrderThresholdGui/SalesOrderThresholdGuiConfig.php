<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesOrderThresholdGui;

interface SalesOrderThresholdGuiConfig
{
    /**
     * @see \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE const.
     *
     * @var string
     */
    public const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_HARD
     *
     * @var string
     */
    public const GROUP_HARD = 'Hard';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::GROUP_SOFT
     *
     * @var string
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD
     *
     * @var string
     */
    public const HARD_TYPE_STRATEGY = 'hard-minimum-threshold';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-minimum-threshold';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-minimum-threshold-fixed-fee';

    /**
     * @deprecated Will be removed in the next major.
     *
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE
     *
     * @var string
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-minimum-threshold-flexible-fee';
}
