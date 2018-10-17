<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageRepository extends AbstractRepository implements CategoryImageRepositoryInterface
{
    /**
     * @param int $categoryId
     * @param array $excludeIdCategoryImageSets
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsByCategoryId(int $categoryId, array $excludeIdCategoryImageSets = [])
    {
        return $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByFkCategory($categoryId)
            ->filterByIdCategoryImageSet($excludeIdCategoryImageSets, Criteria::NOT_IN)
            ->find();
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImage|null
     */
    public function findImageSetById(int $idCategoryImageSet)
    {
        return $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->useSpyCategoryImageSetToCategoryImageQuery(null, Criteria::LEFT_JOIN)
            ->orderBySortOrder(Criteria::DESC)
            ->endUse()
            ->findOne();
    }

    /**
     * @param int $idCategoryImageSet
     * @param array $excludeIdCategoryImage
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsToCategoryImageByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = [])
    {
        return $this->getFactory()
            ->createCategoryImageSetToCategoryImageQuery()
            ->useSpyCategoryImageQuery()
            ->filterByIdCategoryImage($excludeIdCategoryImage, Criteria::NOT_IN)
            ->endUse()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->find();
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findDefaultCategoryImageSets(int $idCategory)
    {
        return $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByFkCategory($idCategory)
            ->filterByFkLocale()
            ->find();
    }

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findLocalizedCategoryImageSets(int $idCategory, int $idLocale)
    {
        return $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByFkCategory($idCategory)
            ->filterByFkLocale($idLocale)
            ->find();
    }
}
