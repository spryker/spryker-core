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
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer>
     */
    public function findProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer>
     */
    public function findAllProductMeasurementUnitStorageEntities(): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findProductConcreteMeasurementUnitStorageEntities(array $productIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findAllProductConcreteMeasurementUnitStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function findFilteredProductMeasurementUnitStorageDataTransfers(FilterTransfer $filterTransfer, array $productMeasurementUnitIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findFilteredProductConcreteMeasurementUnitStorageEntities(FilterTransfer $filterTransfer, array $productIds = []): array;

    /**
     * @module ProductMeasurementUnit
     *
     * @param array<int> $salesUnitsIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer>
     */
    public function getProductMeasurementSalesUnitEntityTransfersByIds(array $salesUnitsIds): array;

    /**
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer>
     */
    public function getProductMeasurementUnitEntityTransfersByIds(array $productMeasurementUnitIds): array;
}
