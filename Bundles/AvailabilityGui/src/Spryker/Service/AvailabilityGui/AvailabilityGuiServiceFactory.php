<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AvailabilityGui;

use Spryker\Service\AvailabilityGui\FloatCalculator\FloatCalculator;
use Spryker\Service\AvailabilityGui\FloatCalculator\FloatCalculatorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\AvailabilityGui\AvailabilityGuiConfig getConfig()
 */
class AvailabilityGuiServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\AvailabilityGui\FloatCalculator\FloatCalculatorInterface
     */
    public function createFloatCalculator(): FloatCalculatorInterface
    {
        return new FloatCalculator($this->getConfig());
    }
}
