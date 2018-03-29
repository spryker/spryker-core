<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

interface ProductMeasurementUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductConcreteMeasurementUnitStorageEntities(array $productIds): array;
}
