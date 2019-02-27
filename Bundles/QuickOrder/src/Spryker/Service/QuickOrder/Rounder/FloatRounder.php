<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\QuickOrder\Rounder;

use Spryker\Service\QuickOrder\QuickOrderConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\QuickOrder\QuickOrderConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\QuickOrder\QuickOrderConfig $config
     */
    public function __construct(QuickOrderConfig $config)
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
