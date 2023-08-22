<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

interface ProductMeasurementSalesUnitMapperInterface
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
    ): ProductMeasurementSalesUnitsBackendApiAttributesTransfer;
}
