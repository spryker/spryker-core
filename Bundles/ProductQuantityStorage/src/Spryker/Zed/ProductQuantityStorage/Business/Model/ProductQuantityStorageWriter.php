<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business\Model;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;
use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage;
use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery;

class ProductQuantityStorageWriter implements ProductQuantityStorageWriterInterface
{
    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $productQuantityEntities = $this->getProductQuantityEntities($productIds);
        $productQuantityStorageEntities = $this->getProductQuantityStorageEntities($productIds);
        $mappedProductQuantityStorageEntities = $this->mapProductQuantityStorageEntities($productQuantityStorageEntities);

        foreach ($productQuantityEntities as $productQuantityEntity) {
            $idProduct = $productQuantityEntity->getFkProduct();
                $storageEntity = isset($mappedProductQuantityStorageEntities[$idProduct]) ?
                    $mappedProductQuantityStorageEntities[$idProduct] :
                    new SpyProductQuantityStorage();

                unset($mappedProductQuantityStorageEntities[$idProduct]);

                $storageEntity
                    ->setFkProduct($idProduct)
                    ->setData($this->getStorageEntityData($productQuantityEntity))
                    ->save();
        }

        array_walk_recursive(
            $mappedProductQuantityStorageEntities,
            function (SpyProductQuantityStorage $productQuantityStorageEntity) {
                $productQuantityStorageEntity->delete();
            }
        );
    }

    /**
     * @param \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity $productQuantityEntity
     *
     * @return array
     */
    protected function getStorageEntityData(SpyProductQuantity $productQuantityEntity)
    {
        return (new ProductQuantityStorageTransfer())
            ->fromArray($productQuantityEntity->toArray(), true)
            ->setIdProduct($productQuantityEntity->getFkProduct())
            ->toArray();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity[]
     */
    protected function getProductQuantityEntities(array $productIds)
    {
        return SpyProductQuantityQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage[]
     */
    protected function getProductQuantityStorageEntities(array $productIds)
    {
        return SpyProductQuantityStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage[] $productQuantityStorageEntities
     *
     * @return array
     */
    protected function mapProductQuantityStorageEntities(array $productQuantityStorageEntities)
    {
        $mappedProductMeasurementUnitStorageEntities = [];
        foreach ($productQuantityStorageEntities as $entity) {
            $mappedProductMeasurementUnitStorageEntities[$entity->getFkProduct()] = $entity;
        }

        return $mappedProductMeasurementUnitStorageEntities;
    }
}
