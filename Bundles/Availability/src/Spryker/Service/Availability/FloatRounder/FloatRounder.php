<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability\FloatRounder;

use Spryker\Service\Availability\AvailabilityConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\Availability\AvailabilityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Availability\AvailabilityConfig $config
     */
    public function __construct(AvailabilityConfig $config)
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
