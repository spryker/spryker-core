<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Rounder;

use Spryker\Service\UtilQuantity\UtilQuantityConfig;

class QuantityRounder implements QuantityRounderInterface
{
    /**
     * @var \Spryker\Service\UtilQuantity\UtilQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\UtilQuantity\UtilQuantityConfig $config
     */
    public function __construct(UtilQuantityConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float
    {
        return round($quantity, $this->config->getQuantityRoundingPrecision());
    }
}
