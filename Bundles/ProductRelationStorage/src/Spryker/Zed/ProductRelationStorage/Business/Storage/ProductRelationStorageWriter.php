<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationStorageTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorage;
use Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface;

class ProductRelationStorageWriter implements ProductRelationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductRelationStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
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
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractRelationStorageEntities = $this->findProductStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractRelationStorageEntities as $spyProductAbstractRelationStorageEntity) {
            $spyProductAbstractRelationStorageEntity->delete();
        }
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

            $storedEntities[] = $idProduct;

            if (isset($spyProductAbstractRelationStorageEntities[$idProduct])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productRelations, $spyProductAbstractRelationStorageEntities[$idProduct]);

                continue;
            }

            $this->storeDataSet($spyProductAbstractLocalizedEntity, $productRelations);
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
        $spyProductAbstractRelationStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
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
            $productRelationStorageTransfer->setIsActive($result[$key][ProductRelationStorageTransfer::IS_ACTIVE]);
            $productRelationStorageTransfer->setProductAbstractIds($result[$key][ProductRelationStorageTransfer::PRODUCT_ABSTRACT_IDS]);
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
                        ProductRelationStorageTransfer::PRODUCT_ABSTRACT_IDS => [],
                        ProductRelationStorageTransfer::IS_ACTIVE => $productRelation->getIsActive(),
                    ];
                }
                $relationName = $relationProduct[SpyProductRelationTypeTableMap::COL_KEY];
                $idProductAbstract = $relationProduct[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
                $order = $relationProduct[SpyProductRelationProductAbstractTableMap::COL_ORDER];

                $results[$relationName][ProductRelationStorageTransfer::PRODUCT_ABSTRACT_IDS][$idProductAbstract] = $order;
            }
        }

        return $results;
    }

    /**
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRelationProducts($idProductRelation, $idLocale)
    {
        return $this->queryContainer
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
        return $this->queryContainer->queryProductRelations($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractRelationStorageEntities = $this->queryContainer->queryProductAbstractRelationStorageByIds($productAbstractIds)->find();
        $productAbstractStorageRelationEntitiesById = [];
        foreach ($productAbstractRelationStorageEntities as $productAbstractRelationStorageEntity) {
            $productAbstractStorageRelationEntitiesById[$productAbstractRelationStorageEntity->getFkProductAbstract()] = $productAbstractRelationStorageEntity;
        }

        return $productAbstractStorageRelationEntitiesById;
    }
}
