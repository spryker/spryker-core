<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductMeasurementUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds): array;

    /**
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findAllProductMeasurementUnitStorageEntities(): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductConcreteMeasurementUnitStorageEntities(array $productIds): array;

    /**
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
     */
    public function findAllProductConcreteMeasurementUnitStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productMeasurementUnitStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductMeasurementUnitStorageEntitiesByOffsetAndLimitFilteredByIds(FilterTransfer $filterTransfer, array $productMeasurementUnitStorageEntityIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteMeasurementUnitStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
     */
    public function findProductConcreteMeasurementUnitStorageEntitiesByOffsetAndLimitFilteredByIds(FilterTransfer $filterTransfer, array $productConcreteMeasurementUnitStorageEntityIds = []): array;
}
