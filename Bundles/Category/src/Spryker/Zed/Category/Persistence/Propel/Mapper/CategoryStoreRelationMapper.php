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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryStore[]|\Propel\Runtime\Collection\ObjectCollection $categoryStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapCategoryStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $categoryStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $storeRelationTransfer->addIdStores($categoryStoreEntity->getFkStore());
            $storeRelationTransfer->addStores(
                $this->mapStoreEntityToStoreTransfer($categoryStoreEntity->getSpyStore(), new StoreTransfer())
            );
        }

        return $storeRelationTransfer;
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
}
