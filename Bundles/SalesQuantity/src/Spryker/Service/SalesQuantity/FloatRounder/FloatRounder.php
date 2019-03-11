<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesQuantity\FloatRounder;

use Spryker\Service\SalesQuantity\SalesQuantityConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\SalesQuantity\SalesQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\SalesQuantity\SalesQuantityConfig $config
     */
    public function __construct(SalesQuantityConfig $config)
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

    /**
     * @param float $value
     *
     * @return int
     */
    public function roundToInt(float $value): int
    {
        return (int)round($value, $this->config->getRoundPrecision(), $this->config->getRoundMode());
    }
}
