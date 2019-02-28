<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oms\Rounder;

use Spryker\Service\Oms\OmsConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\Oms\OmsConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Oms\OmsConfig $config
     */
    public function __construct(OmsConfig $config)
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
