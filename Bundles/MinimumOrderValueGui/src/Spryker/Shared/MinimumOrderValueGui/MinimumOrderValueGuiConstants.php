<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MinimumOrderValueGui;

interface MinimumOrderValueGuiConstants
{
    public const MINIMUM_ORDER_VALUE_DEFAULT_LOCALE = 'default';
    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface::GROUP_HARD
     */
    public const GROUP_HARD = 'Hard';
    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface::GROUP_SOFT
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\HardThresholdStrategy::STRATEGY_KEY
     */
    public const HARD_TYPE_STRATEGY = 'hard-threshold';
    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithMessageStrategy::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-threshold';
    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFixedFeeStrategy::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-threshold-fixed-fee';
    /**
     * @uses \Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFlexibleFeeStrategy::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-threshold-flexible-fee';
}
