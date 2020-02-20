<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantSwitcher;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantSwitcherConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    public const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var bool
     */
    protected const ENABLE_MERCHANT_SWITCHER = true;

    /**
     * Specification:
     * - Enables/disables merchant switcher functionality.
     *
     * @api
     *
     * @return bool
     */
    public function isMerchantSwitcherEnabled(): bool
    {
        return static::ENABLE_MERCHANT_SWITCHER;
    }
}
