<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStoragePersistenceFactory getFactory()
 */
class ProductMeasurementUnitStorageEntityManager extends AbstractEntityManager implements ProductMeasurementUnitStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity
     *
     * @return void
     */
    public function saveProductMeasurementUnitStorageEntity(SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntity): void
    {
        $productMeasurementUnitStorageEntity->requireFkProductMeasurementUnit();

        $spyProductMeasurementUnitStorageEntity = $this->getFactory()
            ->createProductMeasurementUnitStorageQuery()
            ->filterByFkProductMeasurementUnit($productMeasurementUnitStorageEntity->getFkProductMeasurementUnit())
            ->findOneOrCreate();

        $this->getFactory()
            ->createProductMeasurementUnitStorageMapper()
            ->hydrateSpyProductMeasurementUnitStorageEntity($spyProductMeasurementUnitStorageEntity, $productMeasurementUnitStorageEntity)
            ->save();
    }

    /**
     * @param int $idProductMeasurementUnitStorage
     *
     * @return void
     */
    public function deleteProductMeasurementUnitStorage(int $idProductMeasurementUnitStorage): void
    {
        $spyProductMeasurementUnitStorageEntity = $this->getFactory()
            ->createProductMeasurementUnitStorageQuery()
            ->filterByIdProductMeasurementUnitStorage($idProductMeasurementUnitStorage)
            ->findOne();

        $spyProductMeasurementUnitStorageEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity
     *
     * @return void
     */
    public function saveProductConcreteMeasurementUnitStorageEntity(SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity): void
    {
        $productConcreteMeasurementUnitStorageEntity->requireFkProduct();

        $spyProductConcreteMeasurementUnitStorageEntity = $this->getFactory()
            ->createProductConcreteMeasurementUnitStorageQuery()
            ->filterByFkProduct($productConcreteMeasurementUnitStorageEntity->getFkProduct())
            ->findOneOrCreate();

        $this->getFactory()
            ->createProductConcreteMeasurementUnitStorageMapper()
            ->hydrateSpyProductMeasurementUnitStorageEntity(
                $spyProductConcreteMeasurementUnitStorageEntity,
                $productConcreteMeasurementUnitStorageEntity
            )
            ->save();
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function deleteProductConcreteMeasurementUnitStorage(int $idProduct): void
    {
        $spyProductConcreteMeasurementUnitStorageEntity = $this->getFactory()
            ->createProductConcreteMeasurementUnitStorageQuery()
            ->filterByFkProduct($idProduct)
            ->findOne();

        $spyProductConcreteMeasurementUnitStorageEntity->delete();
    }
}
