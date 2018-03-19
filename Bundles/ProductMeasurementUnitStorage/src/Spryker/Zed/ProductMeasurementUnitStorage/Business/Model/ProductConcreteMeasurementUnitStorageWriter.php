<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorageQuery;

class ProductConcreteMeasurementUnitStorageWriter implements ProductConcreteMeasurementUnitStorageWriterInterface
{
    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $productConcreteMeasurementUnitEntities = $this->getProductConcreteMeasurementUnitEntities($productIds);

        $productConcreteMeasurementUnitStorageEntities = $this->getProductConcreteMeasurementUnitStorageEntities($productIds);
        $mappedProductConcreteMeasurementUnitStorageEntities = $this->mapProductConcreteMeasurementUnitStorageEntities($productConcreteMeasurementUnitStorageEntities);

        foreach ($productConcreteMeasurementUnitEntities as $productConcreteMeasurementUnitEntity) {
            $idProduct = $productConcreteMeasurementUnitEntity->getIdProduct();

            $storageEntity = isset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct]) ?
                $mappedProductConcreteMeasurementUnitStorageEntities[$idProduct] :
                new SpyProductConcreteMeasurementUnitStorage();

            unset($mappedProductConcreteMeasurementUnitStorageEntities[$idProduct]);

            $storageEntity
                ->setFkProduct($idProduct)
                ->setData($this->getStorageEntityData($productConcreteMeasurementUnitEntity))
                ->save();
        }

        array_walk_recursive(
            $mappedProductConcreteMeasurementUnitStorageEntities,
            function (SpyProductConcreteMeasurementUnitStorage $productConcreteMeasurementUnitStorageEntity) {
                $productConcreteMeasurementUnitStorageEntity->delete();
            }
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function getStorageEntityData(SpyProduct $productEntity)
    {
        $salesUnits = [];
        foreach ($productEntity->getSpyProductMeasurementSalesUnits() as $productMeasurementSalesUnitEntity) {
            $salesUnits[] = [
                "measurement_unit_id" => $productMeasurementSalesUnitEntity->getFkProductMeasurementUnit(),
                "factor" => (int)$productMeasurementSalesUnitEntity->getFactor(),
                "precision" => (int)$productMeasurementSalesUnitEntity->getPrecision(),
                "is_display" => (bool)$productMeasurementSalesUnitEntity->getIsDisplay(),
                "is_default" => (bool)$productMeasurementSalesUnitEntity->getIsDefault(),
            ];
        }

        return [
            'base_unit' => [
                "measurement_unit_id" => $productEntity->getSpyProductAbstract()->getProductMeasurementBaseUnit()->getFkProductMeasurementUnit(),
                "is_sales_unit" => (bool)$productEntity->getSpyProductAbstract()->getProductMeasurementBaseUnit()->getIsSalesUnit(),
            ],
            "sales_units" => $salesUnits,
        ];
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getProductConcreteMeasurementUnitEntities(array $productIds)
    {
        return SpyProductQuery::create()
            ->filterByIdProduct_In($productIds)
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithProductMeasurementBaseUnit()
            ->endUse()
            ->leftJoinWithSpyProductMeasurementSalesUnit()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage[]
     */
    protected function getProductConcreteMeasurementUnitStorageEntities(array $productIds)
    {
        return SpyProductConcreteMeasurementUnitStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage[] $productConcreteMeasurementUnitStorageEntities
     *
     * @return array
     */
    protected function mapProductConcreteMeasurementUnitStorageEntities(array $productConcreteMeasurementUnitStorageEntities)
    {
        $mappedProductConcreteMeasurementUnitStorageEntities = [];
        foreach ($productConcreteMeasurementUnitStorageEntities as $entity) {
            $mappedProductConcreteMeasurementUnitStorageEntities[$entity->getFkProduct()] = $entity;
        }

        return $mappedProductConcreteMeasurementUnitStorageEntities;
    }
}
