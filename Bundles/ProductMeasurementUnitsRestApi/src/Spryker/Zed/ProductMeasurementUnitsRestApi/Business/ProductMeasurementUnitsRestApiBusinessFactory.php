<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnitsRestApi\Business\Mapper\SalesUnitMapper;
use Spryker\Zed\ProductMeasurementUnitsRestApi\Business\Mapper\SalesUnitMapperInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig getConfig()
 */
class ProductMeasurementUnitsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnitsRestApi\Business\Mapper\SalesUnitMapperInterface
     */
    public function createSalesUnitMapper(): SalesUnitMapperInterface
    {
        return new SalesUnitMapper();
    }
}
