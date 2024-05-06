<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageEntityManager extends AbstractEntityManager implements CategoryStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    public function saveCategoryNodeStorageForStoreAndLocale(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $storeName,
        string $localeName
    ): void {
        $categoryNodeStorageEntity = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByFkCategoryNode($categoryNodeStorageTransfer->getNodeId())
            ->filterByStore($storeName)
            ->filterByLocale($localeName)
            ->findOneOrCreate();

        $categoryNodeStorageEntity = $this->getFactory()
            ->createCategoryNodeStorageMapper()
            ->mapCategoryNodeStorageTransferToCategoryNodeStorageEntity($categoryNodeStorageTransfer, $categoryNodeStorageEntity);

        $categoryNodeStorageEntity->save();
    }

    /**
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return void
     */
    public function deleteCategoryNodeStoragesForStoreAndLocale(array $categoryNodeIds, string $localeName, string $storeName): void
    {
        if ($categoryNodeIds === []) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryNodeStorageCollection */
        $categoryNodeStorageCollection = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->filterByLocale($localeName)
            ->filterByStore($storeName)
            ->find();
        $categoryNodeStorageCollection->delete();
    }

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageByCategoryNodeIds(array $categoryNodeIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryNodeStorageCollection */
        $categoryNodeStorageCollection = $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->find();
        $categoryNodeStorageCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTreeStorageTransfer $categoryTreeStorageTransfer
     *
     * @return void
     */
    public function saveCategoryTreeStorage(CategoryTreeStorageTransfer $categoryTreeStorageTransfer): void
    {
        $categoryTreeStorageEntity = $this->getFactory()
            ->createSpyCategoryTreeStorageQuery()
            ->filterByStore($categoryTreeStorageTransfer->getStore())
            ->filterByLocale($categoryTreeStorageTransfer->getLocale())
            ->findOneOrCreate();

        $categoryTreeStorageEntity = $this->getFactory()
            ->createCategoryTreeStorageMapper()
            ->mapCategoryTreeStorageTransferToCategoryTreeStorageEntity($categoryTreeStorageTransfer, $categoryTreeStorageEntity);

        $categoryTreeStorageEntity->save();
    }

    /**
     * @return void
     */
    public function deleteCategoryTreeStorageCollection(): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryTreeStorageCollection */
        $categoryTreeStorageCollection = $this->getFactory()
            ->createSpyCategoryTreeStorageQuery()
            ->find();
        $categoryTreeStorageCollection->delete();
    }
}
