<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageRepository extends AbstractRepository implements CategoryImageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findCategoryImageSetsByCategoryId(int $categoryId, array $excludeIdCategoryImageSets = []): array
    {
        $categoryImageSetEntityCollection = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->joinWithSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
            ->joinWithSpyCategoryImage()
            ->endUse()
            ->filterByFkCategory($categoryId)
            ->filterByIdCategoryImageSet($excludeIdCategoryImageSets, Criteria::NOT_IN)
            ->find();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSetCollection($categoryImageSetEntityCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function findCategoryImagesByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = []): array
    {
        $categoryImageCollection = $this->getFactory()
            ->createCategoryImageQuery()
            ->useSpyCategoryImageSetToCategoryImageQuery()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($excludeIdCategoryImage, Criteria::NOT_IN)
            ->endUse()
            ->find();

        $categoryImageSetEntity = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByIdCategoryImageSet()
            ->findOne();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageCollection($categoryImageCollection, $categoryImageSetEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function findDefaultCategoryImageSets(int $idCategory): array
    {
        $categoryImageSets = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByFkCategory($idCategory)
            ->filterByFkLocale()
            ->find();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSetCollection($categoryImageSets);
    }

    /**
     * {@inheritdoc}
     */
    public function findLocalizedCategoryImageSets(int $idCategory, int $idLocale): array
    {
        $categoryImageSets = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByFkCategory($idCategory)
            ->filterByFkLocale($idLocale)
            ->find();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSetCollection($categoryImageSets);
    }

    /**
     * @param int|null $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function findOrCreateCategoryImageSetById(?int $idCategoryImageSet): CategoryImageSetTransfer
    {
        $categoryImageSetEntity = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->findOneOrCreate();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSet($categoryImageSetEntity);
    }
}
