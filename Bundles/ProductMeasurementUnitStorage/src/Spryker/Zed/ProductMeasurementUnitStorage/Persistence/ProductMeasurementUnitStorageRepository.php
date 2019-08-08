<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStoragePersistenceFactory getFactory()
 */
class ProductMeasurementUnitStorageRepository extends AbstractRepository implements ProductMeasurementUnitStorageRepositoryInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
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
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findAllProductMeasurementUnitStorageEntities(): array
    {
        $query = $this->getFactory()->createProductMeasurementUnitStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
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
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
     */
    public function findAllProductConcreteMeasurementUnitStorageEntities(): array
    {
        $query = $this->getFactory()->createProductConcreteMeasurementUnitStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function findFilteredProductMeasurementUnitStorageEntities(FilterTransfer $filterTransfer, array $productMeasurementUnitIds = []): array
    {
        $query = $this->getFactory()->createProductMeasurementUnitStorageQuery();

        if ($productMeasurementUnitIds) {
            $query->filterByFkProductMeasurementUnit_In($productMeasurementUnitIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer[]
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
     * @param int[] $salesUnitsIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
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
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
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
