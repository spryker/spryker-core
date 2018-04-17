<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;

interface ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity(int $idProductMeasurementSalesUnit): SpyProductMeasurementSalesUnitEntityTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct(int $idProduct): array;
}
