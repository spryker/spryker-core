<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer;

interface SalesUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer
     */
    public function mapProductMeasurementSalesUnitTransferToRestSalesUnitsAttributesTransfer(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer
    ): RestSalesUnitsAttributesTransfer;
}
