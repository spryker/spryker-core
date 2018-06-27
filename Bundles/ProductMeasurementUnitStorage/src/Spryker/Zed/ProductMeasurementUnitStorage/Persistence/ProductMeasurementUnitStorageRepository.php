<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

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
     * @return array|\Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array
    {
        return $this->getFactory()
            ->createProductMeasurementUnitRepository()
            ->findAllProductMeasurementUnitTransfers();
    }

    /**
     * @return array|\Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductConcreteMeasurementUnitTransfers(): array
    {
        return $this->getFactory()
            ->createProductConcreteMeasurementUnitRepository()
            ->findAllProductMeasurementUnitTransfers();
    }
}
