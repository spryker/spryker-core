<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation\FloatConverter;

use Spryker\Service\Calculation\CalculationConfig;

class FloatConverter implements FloatConverterInterface
{
    /**
     * @var \Spryker\Service\Calculation\CalculationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Calculation\CalculationConfig $config
     */
    public function __construct(CalculationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convert(float $value): int
    {
        return (int)round($value, $this->config->getRoundPrecision(), $this->config->getRoundMode());
    }
}
