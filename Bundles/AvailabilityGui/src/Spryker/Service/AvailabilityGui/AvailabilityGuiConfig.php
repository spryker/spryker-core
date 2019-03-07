<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui;

use Spryker\Service\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Service\AvailabilityGui\AvailabilityGuiServiceFactory getFactory()
 */
class AvailabilityGuiConfig extends AbstractBundleConfig
{
    protected const PRECISION = 2;

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return static::PRECISION;
    }
}
