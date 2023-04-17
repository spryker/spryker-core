<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductMeasurementSalesUnitMapper;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductMeasurementSalesUnitMapperInterface;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductPackagingUnitPickingListItemsMapper;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductPackagingUnitPickingListItemsMapperInterface;

class ProductPackagingUnitsBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductPackagingUnitPickingListItemsMapperInterface
     */
    public function createProductPackagingUnitPickingListItemsMapper(): ProductPackagingUnitPickingListItemsMapperInterface
    {
        return new ProductPackagingUnitPickingListItemsMapper(
            $this->createProductMeasurementSalesUnitMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductMeasurementSalesUnitMapperInterface
     */
    public function createProductMeasurementSalesUnitMapper(): ProductMeasurementSalesUnitMapperInterface
    {
        return new ProductMeasurementSalesUnitMapper();
    }
}
