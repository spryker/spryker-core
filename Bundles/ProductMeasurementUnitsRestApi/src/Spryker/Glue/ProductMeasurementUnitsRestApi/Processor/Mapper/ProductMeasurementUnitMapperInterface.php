<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer;

interface ProductMeasurementUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUniTransfer
     * @param \Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer $restProductMeasurementUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer
     */
    public function mapProductMeasurementUnitTransferToRestProductMeasurementUnitsAttributesTransfer(
        ProductMeasurementUnitTransfer $productMeasurementUniTransfer,
        RestProductMeasurementUnitsAttributesTransfer $restProductMeasurementUnitsAttributesTransfer
    ): RestProductMeasurementUnitsAttributesTransfer;
}
