<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnit;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductMeasurementUnit\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitExpander;

class ProductMeasurementUnitFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductMeasurementUnit\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitExpanderInterface
     */
    public function createProductMeasurementSalesUnitExpander()
    {
        return new ProductMeasurementSalesUnitExpander();
    }
}
