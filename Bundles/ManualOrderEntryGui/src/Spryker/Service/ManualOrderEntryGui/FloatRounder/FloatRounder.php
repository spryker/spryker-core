<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ManualOrderEntryGui\FloatRounder;

use Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiConfig $config
     */
    public function __construct(ManualOrderEntryGuiConfig $config)
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
