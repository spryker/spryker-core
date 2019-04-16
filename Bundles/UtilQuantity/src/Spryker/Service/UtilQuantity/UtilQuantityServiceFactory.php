<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilQuantity\Calculator\PrecisionCalculator;
use Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface;
use Spryker\Service\UtilQuantity\Calculator\QuantityCalculator;
use Spryker\Service\UtilQuantity\Calculator\QuantityCalculatorInterface;
use Spryker\Service\UtilQuantity\Comparator\QuantityComparator;
use Spryker\Service\UtilQuantity\Comparator\QuantityComparatorInterface;

class UtilQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilQuantity\Calculator\QuantityCalculatorInterface
     */
    public function createQuantityCalculator(): QuantityCalculatorInterface
    {
        return new QuantityCalculator($this->createPrecisionCalculator());
    }

    /**
     * @return \Spryker\Service\UtilQuantity\Comparator\QuantityComparatorInterface
     */
    public function createQuantityComparator(): QuantityComparatorInterface
    {
        return new QuantityComparator($this->createPrecisionCalculator());
    }

    /**
     * @return \Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface
     */
    public function createPrecisionCalculator(): PrecisionCalculatorInterface
    {
        return new PrecisionCalculator();
    }
}
