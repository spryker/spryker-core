<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\Base\SpyProductMeasurementUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorageQuery;

class ProductMeasurementUnitStorageWriter implements ProductMeasurementUnitStorageWriterInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publish(array $productMeasurementUnitIds)
    {
        $productMeasurementUnitEntities = $this->getProductMeasurementUnitEntities($productMeasurementUnitIds);
        $productMeasurementUnitStorageEntities = $this->getProductMeasurementUnitStorageEntities($productMeasurementUnitIds);
        $mappedProductMeasurementUnitStorageEntities = $this->mapProductMeasurementUnitStorageEntities($productMeasurementUnitStorageEntities);

        foreach ($productMeasurementUnitEntities as $productMeasurementUnitEntity) {
            $idProductMeasurementUnit = $productMeasurementUnitEntity->getIdProductMeasurementUnit();
            $storageEntity = isset($mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit]) ?
                $mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit] :
                new SpyProductMeasurementUnitStorage();

            unset($mappedProductMeasurementUnitStorageEntities[$idProductMeasurementUnit]);

            $storageEntity
                ->setFkProductMeasurementUnit($idProductMeasurementUnit)
                ->setData($this->getStorageEntityData($productMeasurementUnitEntity))
                ->save();
        }

        array_walk_recursive(
            $mappedProductMeasurementUnitStorageEntities,
            function (SpyProductMeasurementUnitStorage $productMeasurementUnitStorageEntity) {
                $productMeasurementUnitStorageEntity->delete();
            }
        );
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\Base\SpyProductMeasurementUnit $productMeasurementUnitEntity
     *
     * @return array
     */
    protected function getStorageEntityData(SpyProductMeasurementUnit $productMeasurementUnitEntity)
    {
        return (new ProductMeasurementUnitStorageTransfer())
            ->fromArray($productMeasurementUnitEntity->toArray(), true)
            ->setId($productMeasurementUnitEntity->getIdProductMeasurementUnit())
            ->toArray();
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\Base\SpyProductMeasurementUnit[]
     */
    protected function getProductMeasurementUnitEntities(array $productMeasurementUnitIds)
    {
        return SpyProductMeasurementUnitQuery::create()
            ->filterByIdProductMeasurementUnit_In($productMeasurementUnitIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage[]
     */
    protected function getProductMeasurementUnitStorageEntities(array $productMeasurementUnitIds)
    {
        return SpyProductMeasurementUnitStorageQuery::create()
            ->filterByFkProductMeasurementUnit_In($productMeasurementUnitIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage[] $productMeasurementUnitStorageEntities
     *
     * @return array
     */
    protected function mapProductMeasurementUnitStorageEntities(array $productMeasurementUnitStorageEntities)
    {
        $mappedProductMeasurementUnitStorageEntities = [];
        foreach ($productMeasurementUnitStorageEntities as $entity) {
            $mappedProductMeasurementUnitStorageEntities[$entity->getFkProductMeasurementUnit()] = $entity;
        }

        return $mappedProductMeasurementUnitStorageEntities;
    }
}
