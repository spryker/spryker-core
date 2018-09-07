<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MinimumOrderValueGui;

interface MinimumOrderValueGuiConfig
{
    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::GROUP_HARD
     */
    public const GROUP_HARD = 'Hard';
    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::GROUP_SOFT
     */
    public const GROUP_SOFT = 'Soft';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::THRESHOLD_STRATEGY_KEY_HARD
     */
    public const HARD_TYPE_STRATEGY = 'hard-threshold';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::THRESHOLD_STRATEGY_KEY_SOFT
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-threshold';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-threshold-flexible-fee';
}
