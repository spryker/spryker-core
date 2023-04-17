<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiProductMeasurementSalesUnitsAttributesTransfer;
use Generated\Shared\Transfer\ApiProductMeasurementUnitsAttributesTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

class ProductMeasurementSalesUnitMapper implements ProductMeasurementSalesUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\ApiProductMeasurementSalesUnitsAttributesTransfer $apiProductMeasurementSalesUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiProductMeasurementSalesUnitsAttributesTransfer
     */
    public function mapProductMeasurementSalesUnitTransferToApiProductMeasurementSalesUnitsAttributesTransfer(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        ApiProductMeasurementSalesUnitsAttributesTransfer $apiProductMeasurementSalesUnitsAttributesTransfer
    ): ApiProductMeasurementSalesUnitsAttributesTransfer {
        $apiProductMeasurementSalesUnitsAttributesTransfer->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $apiProductMeasurementUnitsAttributesTransfer = $this->mapProductMeasurementUnitTransferToApiProductMeasurementUnitsAttributesTransfer(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail(),
            new ApiProductMeasurementUnitsAttributesTransfer(),
        );

        return $apiProductMeasurementSalesUnitsAttributesTransfer->setProductMeasurementUnit($apiProductMeasurementUnitsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     * @param \Generated\Shared\Transfer\ApiProductMeasurementUnitsAttributesTransfer $apiProductMeasurementUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiProductMeasurementUnitsAttributesTransfer
     */
    protected function mapProductMeasurementUnitTransferToApiProductMeasurementUnitsAttributesTransfer(
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer,
        ApiProductMeasurementUnitsAttributesTransfer $apiProductMeasurementUnitsAttributesTransfer
    ): ApiProductMeasurementUnitsAttributesTransfer {
        return $apiProductMeasurementUnitsAttributesTransfer->fromArray($productMeasurementUnitTransfer->toArray(), true);
    }
}
