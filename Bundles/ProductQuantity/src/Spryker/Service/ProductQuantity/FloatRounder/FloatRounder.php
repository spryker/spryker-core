<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity\FloatRounder;

use Spryker\Service\ProductQuantity\ProductQuantityConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\ProductQuantity\ProductQuantityConfig $config
     */
    public function __construct(ProductQuantityConfig $config)
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
