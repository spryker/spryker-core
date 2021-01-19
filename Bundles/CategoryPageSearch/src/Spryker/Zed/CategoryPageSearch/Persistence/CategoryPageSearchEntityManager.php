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
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchByCategoryNodeIds(array $categoryNodeIds): void
    {
        $this->getFactory()
            ->createSpyCategoryNodePageSearchQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->find()
            ->delete();
    }
}
