<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;

interface ProductMeasurementUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity
     *
     * @return void
     */
    public function saveProductMeasurementUnitStorageEntity(SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity);

    /**
     * @param int $idProductMeasurementUnitStorage
     *
     * @return void
     */
    public function deleteProductMeasurementUnitStorage($idProductMeasurementUnitStorage);

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity
     *
     * @return void
     */
    public function saveProductConcreteMeasurementUnitStorageEntity(SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity);

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function deleteProductConcreteMeasurementUnitStorage($idProduct);
}
