<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationStorageTransfer;
use Generated\Shared\Transfer\StorageProductRelationsTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\Communication\ProductRelationStorageCommunicationFactory getFactory()
 */
class AbstractProductRelationStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $productRelationEntities = $this->findProductRelationAbstractEntities($productAbstractIds);
        $productRelations = [];
        foreach ($productRelationEntities as $productRelationEntity) {
            $productRelations[$productRelationEntity->getFkProductAbstract()][] = $productRelationEntity;
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractRelationStorageEntities = $this->findProductStorageEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($spyProductAbstractLocalizedAttributeEntities, $spyProductAbstractRelationStorageEntities, $productRelations);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractRelationStorageEntities
     * @param array $productRelations
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractRelationStorageEntities, array $productRelations)
    {
        $storedEntities = [];
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            if (in_array($idProduct, $storedEntities)) {
                continue;
            }

            if (isset($spyProductAbstractRelationStorageEntities[$idProduct])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productRelations, $spyProductAbstractRelationStorageEntities[$idProduct]);
            } else {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productRelations);
            }
            $storedEntities[] = $idProduct;
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $productRelations
     * @param \Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorage|null $spyProductAbstractRelationStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $productRelations, ?SpyProductAbstractRelationStorage $spyProductAbstractRelationStorageEntity = null)
    {
        if ($spyProductAbstractRelationStorageEntity === null) {
            $spyProductAbstractRelationStorageEntity = new SpyProductAbstractRelationStorage();
        }

        if (empty($productRelations[$spyProductAbstractLocalizedEntity->getFkProductAbstract()])) {
            if (!$spyProductAbstractRelationStorageEntity->isNew()) {
                $spyProductAbstractRelationStorageEntity->delete();
            }

            return;
        }

        $productRelationStorageTransfers = $this->getProductRelationStorageTransfers($spyProductAbstractLocalizedEntity, $productRelations);
        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();
        $productAbstractRelationStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $productAbstractRelationStorageTransfer->setProductRelations($productRelationStorageTransfers);

        $spyProductAbstractRelationStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractRelationStorageEntity->setData($productAbstractRelationStorageTransfer->toArray());
        $spyProductAbstractRelationStorageEntity->setStore($this->getStoreName());
        $spyProductAbstractRelationStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $productRelations
     *
     * @return \ArrayObject
     */
    protected function getProductRelationStorageTransfers(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $productRelations)
    {
        $allProductRelations = [];
        foreach ($productRelations as $idProductAbstract => $productRelation) {
            if ($spyProductAbstractLocalizedEntity->getFkProductAbstract() === $idProductAbstract) {
                $allProductRelations = array_merge($allProductRelations, $productRelation);
            }
        }

        $result = $this->findRelatedProductAbstract($allProductRelations, $spyProductAbstractLocalizedEntity->getFkLocale());
        $productRelationStorageTransfers = new ArrayObject();
        foreach ($result as $key => $value) {
            $productRelationStorageTransfer = new ProductRelationStorageTransfer();
            $productRelationStorageTransfer->setKey($key);
            $productRelationStorageTransfer->setIsActive($result[$key][StorageProductRelationsTransfer::IS_ACTIVE]);
            $productRelationStorageTransfer->setProductAbstractIds($result[$key][StorageProductRelationsTransfer::ABSTRACT_PRODUCTS]);
            $productRelationStorageTransfers[] = $productRelationStorageTransfer;
        }

        return $productRelationStorageTransfers;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation[] $productRelations
     * @param int $idLocale
     *
     * @return array
     */
    public function findRelatedProductAbstract(array $productRelations, $idLocale)
    {
        $results = [];
        foreach ($productRelations as $productRelation) {
            $relationProducts = $this->findRelationProducts($productRelation->getIdProductRelation(), $idLocale);

            foreach ($relationProducts as $relationProduct) {
                if (!isset($results[$relationProduct[SpyProductRelationTypeTableMap::COL_KEY]])) {
                    $results[$relationProduct[SpyProductRelationTypeTableMap::COL_KEY]] = [
                        StorageProductRelationsTransfer::ABSTRACT_PRODUCTS => [],
                        StorageProductRelationsTransfer::IS_ACTIVE => $productRelation->getIsActive(),
                    ];
                }
                $relationName = $relationProduct[SpyProductRelationTypeTableMap::COL_KEY];
                $idProductAbstract = $relationProduct[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
                $order = $relationProduct[SpyProductRelationProductAbstractTableMap::COL_ORDER];

                $results[$relationName][StorageProductRelationsTransfer::ABSTRACT_PRODUCTS][$idProductAbstract] = $order;
            }
        }

        return $results;
    }

    /**
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRelationProducts($idProductRelation, $idLocale)
    {
        return $this->getQueryContainer()
            ->queryProductRelationWithProductAbstractByIdRelationAndLocale(
                $idProductRelation,
                $idLocale
            )
            ->find();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation[]
     */
    protected function findProductRelationAbstractEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductRelations($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractRelationStorageEntities = $this->getQueryContainer()->queryProductAbstractRelationStorageByIds($productAbstractIds)->find();
        $productAbstractStorageRelationEntitiesById = [];
        foreach ($productAbstractRelationStorageEntities as $productAbstractRelationStorageEntity) {
            $productAbstractStorageRelationEntitiesById[$productAbstractRelationStorageEntity->getFkProductAbstract()] = $productAbstractRelationStorageEntity;
        }

        return $productAbstractStorageRelationEntitiesById;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
