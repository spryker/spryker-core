<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductPackagingUnit\Rounder;

use Spryker\Service\ProductPackagingUnit\ProductPackagingUnitConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitConfig $config
     */
    public function __construct(ProductPackagingUnitConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return round($value, $this->config->getRoundPrecision(), $this->config->getRoundMode());
    }
}
