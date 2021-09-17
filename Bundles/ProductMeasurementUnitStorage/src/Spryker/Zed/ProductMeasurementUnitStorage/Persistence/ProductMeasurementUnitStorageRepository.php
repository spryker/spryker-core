<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStoragePersistenceFactory getFactory()
 */
class ProductMeasurementUnitStorageRepository extends AbstractRepository implements ProductMeasurementUnitStorageRepositoryInterface
{
    /**
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer>
     */
    public function findProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds): array
    {
        if (!$productMeasurementUnitIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductMeasurementUnitStorageQuery()
            ->filterByFkProductMeasurementUnit_In($productMeasurementUnitIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer>
     */
    public function findAllProductMeasurementUnitStorageEntities(): array
    {
        $query = $this->getFactory()->createProductMeasurementUnitStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findProductConcreteMeasurementUnitStorageEntities(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductConcreteMeasurementUnitStorageQuery()
            ->filterByFkProduct_In($productIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findAllProductConcreteMeasurementUnitStorageEntities(): array
    {
        $query = $this->getFactory()->createProductConcreteMeasurementUnitStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function findFilteredProductMeasurementUnitStorageDataTransfers(FilterTransfer $filterTransfer, array $productMeasurementUnitIds = []): array
    {
        $productMeasurementUnitStoragePropelQuery = $this->getFactory()->createProductMeasurementUnitStorageQuery();

        if ($productMeasurementUnitIds) {
            $productMeasurementUnitStoragePropelQuery->filterByFkProductMeasurementUnit_In($productMeasurementUnitIds);
        }

        return $this->buildQueryFromCriteria($productMeasurementUnitStoragePropelQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer>
     */
    public function findFilteredProductConcreteMeasurementUnitStorageEntities(FilterTransfer $filterTransfer, array $productIds = []): array
    {
        $query = $this->getFactory()->createProductConcreteMeasurementUnitStorageQuery();

        if ($productIds) {
            $query->filterByFkProduct_In($productIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @module ProductMeasurementUnit
     *
     * @param array<int> $salesUnitsIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer>
     */
    public function getProductMeasurementSalesUnitEntityTransfersByIds(array $salesUnitsIds): array
    {
        $query = $this->getFactory()->getProductMeasurementSalesUnitQuery();

        if ($salesUnitsIds !== []) {
            $query->filterByIdProductMeasurementSalesUnit_In($salesUnitsIds);
        }

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module ProductMeasurementUnit
     *
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer>
     */
    public function getProductMeasurementUnitEntityTransfersByIds(array $productMeasurementUnitIds): array
    {
        $query = $this->getFactory()->getProductMeasurementUnitQuery();

        if ($productMeasurementUnitIds !== []) {
            $query->filterByIdProductMeasurementUnit_In($productMeasurementUnitIds);
        }

        return $this->buildQueryFromCriteria($query)->find();
    }
}
