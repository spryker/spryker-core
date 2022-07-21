<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryStoreRelationMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategoryStore> $categoryStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapCategoryStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $categoryStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $storeTransfer = $this->mapStoreEntityToStoreTransfer($categoryStoreEntity->getSpyStore(), new StoreTransfer());
            $storeRelationTransfer->addStores($storeTransfer);
            $storeRelationTransfer->addIdStores($storeTransfer->getIdStoreOrFail());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategoryStore> $categoryStoreEntities
     *
     * @return array<\Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function mapCategoryStoreEntitiesToStoreRelationTransfers(ObjectCollection $categoryStoreEntities): array
    {
        $storeRelationTransfers = [];

        $groupedCategoryStoreEntities = $this->getCategoryStoreEntitiesGroupedByIdCategory($categoryStoreEntities);
        foreach ($groupedCategoryStoreEntities as $idCategory => $categoryStoreEntitiesByCategoryId) {
            $storeRelationTransfers[] = $this->mapCategoryStoreEntitiesToStoreRelationTransfer(
                new ObjectCollection($categoryStoreEntitiesByCategoryId),
                (new StoreRelationTransfer())->setIdEntity($idCategory),
            );
        }

        return $storeRelationTransfers;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategoryStore> $categoryStoreEntities
     *
     * @return array<int, array<\Orm\Zed\Category\Persistence\SpyCategoryStore>>
     */
    protected function getCategoryStoreEntitiesGroupedByIdCategory(ObjectCollection $categoryStoreEntities): array
    {
        $groupedCategoryStoreEntities = [];
        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $groupedCategoryStoreEntities[$categoryStoreEntity->getFkCategory()][] = $categoryStoreEntity;
        }

        return $groupedCategoryStoreEntities;
    }
}
