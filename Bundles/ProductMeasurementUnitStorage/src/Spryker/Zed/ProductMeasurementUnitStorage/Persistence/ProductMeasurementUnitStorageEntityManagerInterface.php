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
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductMeasurementUnitStorageEntity(SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntityTransfer): void;

    /**
     * @param int $idProductMeasurementUnitStorage
     *
     * @return void
     */
    public function deleteProductMeasurementUnitStorage(int $idProductMeasurementUnitStorage): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductConcreteMeasurementUnitStorageEntity(SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntityTransfer): void;

    /**
     * @param int $idProductConcreteMeasurementUnitStorage
     *
     * @return void
     */
    public function deleteProductConcreteMeasurementUnitStorage(int $idProductConcreteMeasurementUnitStorage): void;
}
