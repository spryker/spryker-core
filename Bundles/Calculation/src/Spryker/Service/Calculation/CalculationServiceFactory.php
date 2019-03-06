<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation;

use Spryker\Service\Calculation\FloatConverter\FloatConverter;
use Spryker\Service\Calculation\FloatConverter\FloatConverterInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Calculation\CalculationConfig getConfig()
 */
class CalculationServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Calculation\FloatConverter\FloatConverterInterface
     */
    public function createFloatConverter(): FloatConverterInterface
    {
        return new FloatConverter($this->getConfig());
    }
}
