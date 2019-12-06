<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorage;
use Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface;

class ProductAbstractGroupStorageWriter implements ProductAbstractGroupStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductGroupStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
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
        $groupedProductAbstractIds = $this->getGroupedProductAbstractIdsByGroupIds($productAbstractIds);
        $allProductAbstractIds = $this->getProductAbstractIds($groupedProductAbstractIds, $productAbstractIds);

        if (empty($allProductAbstractIds)) {
            return;
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedWithEntities($allProductAbstractIds);
        $spyProductAbstractGroupStorageEntities = $this->findProductAbstractGroupStorageEntitiesByProductAbstractIds($allProductAbstractIds);

        $foundProductAbstractIds = [];
        foreach ($spyProductAbstractLocalizedAttributeEntities as $spyProductAbstractLocalizedAttributeEntity) {
            $foundProductAbstractIds[] = $spyProductAbstractLocalizedAttributeEntity->getFkProductAbstract();
        }

        $uniqueProductAbstractIds = array_unique($foundProductAbstractIds);
        $this->storeData($uniqueProductAbstractIds, $spyProductAbstractGroupStorageEntities, $groupedProductAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractGroupStorageEntities = $this->findProductAbstractGroupStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractGroupStorageEntities as $spyProductAbstractGroupStorageEntity) {
            $spyProductAbstractGroupStorageEntity->delete();
        }
    }

    /**
     * @param array $uniqueProductAbstractIds
     * @param array $spyProductAbstractGroupStorageEntities
     * @param array $groupedProductAbstractIds
     *
     * @return void
     */
    protected function storeData(array $uniqueProductAbstractIds, array $spyProductAbstractGroupStorageEntities, array $groupedProductAbstractIds)
    {
        foreach ($uniqueProductAbstractIds as $productAbstractId) {
            if (isset($spyProductAbstractGroupStorageEntities[$productAbstractId])) {
                $this->storeDataSet($productAbstractId, $groupedProductAbstractIds, $spyProductAbstractGroupStorageEntities[$productAbstractId]);

                continue;
            }

            $this->storeDataSet($productAbstractId, $groupedProductAbstractIds);
        }
    }

    /**
     * @param int $productAbstractId
     * @param array $groupedProductAbstractIds
     * @param \Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorage|null $spyProductStorageGroupEntity
     *
     * @return void
     */
    protected function storeDataSet($productAbstractId, array $groupedProductAbstractIds, ?SpyProductAbstractGroupStorage $spyProductStorageGroupEntity = null)
    {
        if ($spyProductStorageGroupEntity === null) {
            $spyProductStorageGroupEntity = new SpyProductAbstractGroupStorage();
        }

        $allGroupedProductAbstractIds = $this->mergeRelatedProductAbstractIds($productAbstractId, $groupedProductAbstractIds);
        if (empty($allGroupedProductAbstractIds)) {
            if (!$spyProductStorageGroupEntity->isNew()) {
                $spyProductStorageGroupEntity->delete();
            }

            return;
        }

        $orderedAllGroupedProductAbstractIds = $this->moveSubjectProductToFirstPosition($productAbstractId, array_unique($allGroupedProductAbstractIds));
        $productAbstractGroupStorageTransfer = $this->getProductAbstractGroupStorageTransfer($productAbstractId, $orderedAllGroupedProductAbstractIds);

        $spyProductStorageGroupEntity->setFkProductAbstract($productAbstractId);
        $spyProductStorageGroupEntity->setData($productAbstractGroupStorageTransfer->toArray());
        $spyProductStorageGroupEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductStorageGroupEntity->save();
    }

    /**
     * @param int $productAbstractId
     * @param array $orderedAllGroupedProductAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer
     */
    protected function getProductAbstractGroupStorageTransfer($productAbstractId, array $orderedAllGroupedProductAbstractIds)
    {
        $productAbstractGroupStorageTransfer = new ProductAbstractGroupStorageTransfer();
        $productAbstractGroupStorageTransfer->setIdProductAbstract($productAbstractId);
        $productAbstractGroupStorageTransfer->setGroupProductAbstractIds($orderedAllGroupedProductAbstractIds);

        return $productAbstractGroupStorageTransfer;
    }

    /**
     * @param array $productAbstractIds
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function getGroupedProductAbstractIdsByGroupIds(array $productAbstractIds, array $groupedProductAbstractIds = [])
    {
        $productGroupIds = $this->findProductGroupAbstractEntitiesByProductAbstractIds($productAbstractIds);
        $spyProductAbstractGroupEntities = $this->findProductGroupAbstractEntitiesByProductGroupIds(array_keys($productGroupIds));
        $allProductAbstractIds = [];
        foreach ($spyProductAbstractGroupEntities as $spyProductAbstractGroupEntity) {
            $groupedProductAbstractIds[$spyProductAbstractGroupEntity->getFkProductGroup()][] = $spyProductAbstractGroupEntity->getFkProductAbstract();
            $allProductAbstractIds[] = $spyProductAbstractGroupEntity->getFkProductAbstract();
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedWithGroupEntities($allProductAbstractIds);
        foreach ($spyProductAbstractLocalizedAttributeEntities as $spyProductAbstractLocalizedAttributeEntity) {
            $groupedProductAbstractIds = $this->getGroupedProductAbstractIds($spyProductAbstractLocalizedAttributeEntity, $groupedProductAbstractIds);
        }

        return $groupedProductAbstractIds;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedAttributeEntity
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function getGroupedProductAbstractIds(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedAttributeEntity, array $groupedProductAbstractIds)
    {
        foreach ($spyProductAbstractLocalizedAttributeEntity->getSpyProductAbstract()->getSpyProductAbstractGroups() as $spyProductAbstractGroup) {
            $idProductGroup = $spyProductAbstractGroup->getFkProductGroup();
            if (!isset($groupedProductAbstractIds[$idProductGroup])) {
                $result = $this->getGroupedProductAbstractIdsByGroupIds([$spyProductAbstractGroup->getFkProductAbstract()], $groupedProductAbstractIds);
                $groupedProductAbstractIds[$idProductGroup] = $result[$idProductGroup];
            }
        }

        return $groupedProductAbstractIds;
    }

    /**
     * @param int $productAbstractId
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function mergeRelatedProductAbstractIds($productAbstractId, array $groupedProductAbstractIds)
    {
        $allGroupedProductAbstractIds = [];
        foreach ($groupedProductAbstractIds as $items) {
            if (in_array($productAbstractId, $items)) {
                $allGroupedProductAbstractIds = array_merge($allGroupedProductAbstractIds, $items);
            }
        }

        return $allGroupedProductAbstractIds;
    }

    /**
     * @param array $groupedProductAbstractIds
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductAbstractIds(array $groupedProductAbstractIds, array $productAbstractIds)
    {
        $allProductAbstractId = [];
        foreach ($groupedProductAbstractIds as $groupedProductAbstractId) {
            $allProductAbstractId = array_merge($allProductAbstractId, $groupedProductAbstractId);
        }

        return array_unique(array_merge($allProductAbstractId, $productAbstractIds));
    }

    /**
     * @param int $idProductAbstract
     * @param array $idProductAbstracts
     *
     * @return array
     */
    protected function moveSubjectProductToFirstPosition($idProductAbstract, array $idProductAbstracts)
    {
        $currentProductIndex = array_search($idProductAbstract, $idProductAbstracts);

        if ($currentProductIndex !== false) {
            unset($idProductAbstracts[$currentProductIndex]);
            array_unshift($idProductAbstracts, $idProductAbstract);
        }

        return $idProductAbstracts;
    }

    /**
     * @param array $productGroupIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup[]
     */
    protected function findProductGroupAbstractEntitiesByProductGroupIds(array $productGroupIds)
    {
        return $this->queryContainer->queryProductAbstractGroupByGroupIds($productGroupIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup[]
     */
    protected function findProductGroupAbstractEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractGroupByProductAbstractIds($productAbstractIds)->find()->toKeyIndex('fkProductGroup');
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedWithGroupEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedWithGroupByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedWithEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractGroupStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractGroupStorageEntities = $this->queryContainer->queryProductAbstractGroupStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesById = [];
        foreach ($productAbstractGroupStorageEntities as $productAbstractGroupStorageEntity) {
            $productAbstractStorageEntitiesById[$productAbstractGroupStorageEntity->getFkProductAbstract()] = $productAbstractGroupStorageEntity;
        }

        return $productAbstractStorageEntitiesById;
    }
}
