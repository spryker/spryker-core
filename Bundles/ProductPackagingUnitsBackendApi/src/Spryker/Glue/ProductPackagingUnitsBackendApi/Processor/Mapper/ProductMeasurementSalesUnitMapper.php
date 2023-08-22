<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

class ProductMeasurementSalesUnitMapper implements ProductMeasurementSalesUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitsBackendApiAttributesTransfer $productMeasurementSalesUnitsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitsBackendApiAttributesTransfer
     */
    public function mapProductMeasurementSalesUnitTransferToProductMeasurementSalesUnitsBackendApiAttributesTransfer(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        ProductMeasurementSalesUnitsBackendApiAttributesTransfer $productMeasurementSalesUnitsBackendApiAttributesTransfer
    ): ProductMeasurementSalesUnitsBackendApiAttributesTransfer {
        $productMeasurementSalesUnitsBackendApiAttributesTransfer->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $productMeasurementUnitsBackendApiAttributesTransfer = $this->mapProductMeasurementUnitTransferToProductMeasurementUnitsBackendApiAttributesTransfer(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail(),
            new ProductMeasurementUnitsBackendApiAttributesTransfer(),
        );

        return $productMeasurementSalesUnitsBackendApiAttributesTransfer->setProductMeasurementUnit($productMeasurementUnitsBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitsBackendApiAttributesTransfer $productMeasurementUnitsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitsBackendApiAttributesTransfer
     */
    protected function mapProductMeasurementUnitTransferToProductMeasurementUnitsBackendApiAttributesTransfer(
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer,
        ProductMeasurementUnitsBackendApiAttributesTransfer $productMeasurementUnitsBackendApiAttributesTransfer
    ): ProductMeasurementUnitsBackendApiAttributesTransfer {
        return $productMeasurementUnitsBackendApiAttributesTransfer->fromArray($productMeasurementUnitTransfer->toArray(), true);
    }
}
