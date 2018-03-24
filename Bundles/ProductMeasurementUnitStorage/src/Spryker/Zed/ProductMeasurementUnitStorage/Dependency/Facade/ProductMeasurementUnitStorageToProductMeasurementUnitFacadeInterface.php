<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

interface ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getBaseUnitByIdProduct($idProduct);

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getSalesUnitsByIdProduct($idProduct);
}
