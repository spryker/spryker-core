<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

interface ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit);

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct);
}
