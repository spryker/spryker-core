<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilQuantity\Calculator\QuantityCalculator;
use Spryker\Service\UtilQuantity\Calculator\QuantityCalculatorInterface;

class UtilQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilQuantity\Calculator\QuantityCalculatorInterface
     */
    public function createQuantityCalculator(): QuantityCalculatorInterface
    {
        return new QuantityCalculator();
    }
}
