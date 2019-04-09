<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume;

use Spryker\Service\Kernel\AbstractBundleConfig;

class PriceProductVolumeConfig extends AbstractBundleConfig
{
    protected const MINIMUM_QUANTITY = 1.0;

    /**
     * @return float
     */
    public function getMinimumQuantity(): float
    {
        return static::MINIMUM_QUANTITY;
    }
}
