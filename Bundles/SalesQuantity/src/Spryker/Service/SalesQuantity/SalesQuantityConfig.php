<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesQuantity;

use Spryker\Service\Kernel\AbstractBundleConfig;

class SalesQuantityConfig extends AbstractBundleConfig
{
    protected const ROUND_PRECISION = 2;
    protected const ROUND_MODE = PHP_ROUND_HALF_UP;

    /**
     * @return int
     */
    public function getRoundPrecision(): int
    {
        return static::ROUND_PRECISION;
    }

    /**
     * @return int
     */
    public function getRoundMode(): int
    {
        return static::ROUND_MODE;
    }
}
