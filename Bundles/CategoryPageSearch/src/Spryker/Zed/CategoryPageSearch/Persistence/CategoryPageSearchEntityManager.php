<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Generated\Shared\Transfer\CategoryNodePageSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchPersistenceFactory getFactory()
 */
class CategoryPageSearchEntityManager extends AbstractEntityManager implements CategoryPageSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodePageSearchTransfer $categoryNodePageSearchTransfer
     *
     * @return void
     */
    public function saveCategoryNodePageSearch(CategoryNodePageSearchTransfer $categoryNodePageSearchTransfer): void
    {
        $categoryNodePageSearchEntity = $this->getFactory()
            ->createSpyCategoryNodePageSearchQuery()
            ->filterByFkCategoryNode($categoryNodePageSearchTransfer->getIdCategoryNode())
            ->filterByStore($categoryNodePageSearchTransfer->getStore())
            ->filterByLocale($categoryNodePageSearchTransfer->getLocale())
            ->findOneOrCreate();

        $categoryNodePageSearchEntity = $this->getFactory()
            ->createCategoryNodePageSearchMapper()
            ->mapCategoryNodePageSearchTransferToCategoryNodePageSearchEntity($categoryNodePageSearchTransfer, $categoryNodePageSearchEntity);

        $categoryNodePageSearchEntity->save();
    }

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchByCategoryNodeIds(array $categoryNodeIds): void
    {
        if ($categoryNodeIds === []) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryNodePageSearchCollection */
        $categoryNodePageSearchCollection = $this->getFactory()
            ->createSpyCategoryNodePageSearchQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->find();
        $categoryNodePageSearchCollection->delete();
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchByIdCategoryNodeForLocaleAndStore(int $idCategoryNode, string $localeName, string $storeName): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryNodePageSearchCollection */
        $categoryNodePageSearchCollection = $this->getFactory()
            ->createSpyCategoryNodePageSearchQuery()
            ->filterByFkCategoryNode($idCategoryNode)
            ->filterByLocale($localeName)
            ->filterByStore($storeName)
            ->find();

        $categoryNodePageSearchCollection->delete();
    }
}
