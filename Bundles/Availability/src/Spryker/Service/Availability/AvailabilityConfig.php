<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability;

use Spryker\Service\Kernel\AbstractBundleConfig;

class AvailabilityConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const PATTERN_IS_NEVER_OUT_OF_STOCK = '/true|1/i';

    /**
     * @api
     *
     * @return string
     */
    public function getIsNeverOutOfStockPattern(): string
    {
        return static::PATTERN_IS_NEVER_OUT_OF_STOCK;
    }
}
