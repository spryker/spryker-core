<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilQuantityConfig extends AbstractBundleConfig
{
    protected const QUANTITY_ROUNDING_PRECISION = 0;

    /**
     * @return int
     */
    public function getQuantityRoundingPrecision(): int
    {
        return static::QUANTITY_ROUNDING_PRECISION;
    }
}
