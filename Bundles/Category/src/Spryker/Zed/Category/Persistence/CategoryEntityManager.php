<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryEntityManager extends AbstractEntityManager implements CategoryEntityManagerInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryQuery()
            ->findByIdCategory($idCategory)
            ->delete();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryLocalizedAttributes(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryAttributeQuery()
            ->findByFkCategory($idCategory)
            ->delete();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryNode(int $idCategoryNode): void
    {
        $this->getFactory()
            ->createCategoryNodeQuery()
            ->findByIdCategoryNode($idCategoryNode)
            ->delete();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTable(int $idCategoryNode): void
    {
        $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->filterByFkCategoryNode($idCategoryNode)
            ->_or()
            ->filterByFkCategoryNodeDescendant($idCategoryNode)
            ->find()
            ->delete();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryStoreRelations(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryStoreQuery()
            ->filterByFkCategory($idCategory)
            ->find()
            ->delete();
    }
}
