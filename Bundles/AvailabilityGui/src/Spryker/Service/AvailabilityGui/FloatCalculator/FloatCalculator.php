<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui\Model;

use Spryker\Service\AvailabilityGui\AvailabilityGuiConfig;
use Spryker\Service\Kernel\AbstractService;

class FloatCalculator extends AbstractService implements FloatCalculatorInterface
{
    /**
     * @var \Spryker\Service\AvailabilityGui\AvailabilityGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\AvailabilityGui\AvailabilityGuiConfig $config
     */
    public function __construct(AvailabilityGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return bool
     */
    public function isEqual(float $leftOperand, float $rightOperand): bool
    {
        return bccomp((string)$leftOperand, (string)$rightOperand, $this->config->getPrecision()) === 0;
    }
}
