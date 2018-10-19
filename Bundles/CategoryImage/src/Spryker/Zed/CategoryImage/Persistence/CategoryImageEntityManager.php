<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageEntityManager extends AbstractEntityManager implements CategoryImageEntityManagerInterface
{
    /**
     * @param int|null $idCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage
     */
    public function findOrCreateCategoryImageById(?int $idCategoryImage): SpyCategoryImage
    {
        return $this->getFactory()
            ->createCategoryImageQuery()
            ->filterByIdCategoryImage($idCategoryImage)
            ->findOneOrCreate();
    }

    /**
     * @param int|null $idCategoryImageSet
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    public function findOrCreateCategoryImageSetById(?int $idCategoryImageSet): SpyCategoryImageSet
    {
        return $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->findOneOrCreate();
    }

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage
     */
    public function findOrCreateCategoryImageRelation(int $idCategoryImageSet, int $idCategoryImage)
    {
        return $this->getFactory()
            ->createCategoryImageSetToCategoryImageQuery()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->findOneOrCreate();
    }
}
