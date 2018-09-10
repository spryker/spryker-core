<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantRelationshipMinimumOrderValueGui;

interface MerchantRelationshipMinimumOrderValueGuiConfig
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
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::STRATEGY_KEY
     */
    public const HARD_TYPE_STRATEGY = 'hard-threshold';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_MESSAGE = 'soft-threshold';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FIXED = 'soft-threshold-fixed-fee';

    /**
     * @uses \Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig::STRATEGY_KEY
     */
    public const SOFT_TYPE_STRATEGY_FLEXIBLE = 'soft-threshold-flexible-fee';
}
