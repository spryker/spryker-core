<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesOrderThreshold;

class SalesOrderThresholdConfig
{
    public const GROUP_HARD = 'Hard';
    public const GROUP_SOFT = 'Soft';

    public const THRESHOLD_STRATEGY_KEY_HARD = 'hard-minimum-threshold';
    public const THRESHOLD_STRATEGY_KEY_SOFT = 'soft-minimum-threshold';
    public const THRESHOLD_STRATEGY_KEY_SOFT_FIXED_FEE = 'soft-minimum-threshold-fixed-fee';
    public const THRESHOLD_STRATEGY_KEY_SOFT_FLEXIBLE_FEE = 'soft-minimum-threshold-flexible-fee';

    public const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    public const DEFAULT_TAX_RATE_ISO2CODE = 'DE';

    /**
     * @uses \Spryker\Shared\Tax\TaxConstants::TAX_EXEMPT_PLACEHOLDER
     */
    public const TAX_EXEMPT_PLACEHOLDER = 'Tax Exempt';
}
