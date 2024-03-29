<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageRepository extends AbstractRepository implements CategoryImageRepositoryInterface
{
    /**
     * @param int $idCategory
     * @param array $excludeIdCategoryImageSets
     *
     * @return array<\Generated\Shared\Transfer\CategoryImageSetTransfer>
     */
    public function getCategoryImageSetsByIdCategory(int $idCategory, array $excludeIdCategoryImageSets = []): array
    {
        $categoryImageSetEntityCollection = SpyCategoryImageSetQuery::create()
            ->joinWithSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
                ->joinWithSpyCategoryImage()
            ->endUse()
            ->filterByFkCategory($idCategory)
            ->filterByIdCategoryImageSet($excludeIdCategoryImageSets, Criteria::NOT_IN)
            ->find();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSetCollection($categoryImageSetEntityCollection);
    }

    /**
     * @param int $idCategoryImageSet
     * @param array $excludeIdCategoryImage
     *
     * @return array<\Generated\Shared\Transfer\CategoryImageTransfer>
     */
    public function getCategoryImagesByCategoryImageSetId(int $idCategoryImageSet, array $excludeIdCategoryImage = []): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CategoryImage\Persistence\SpyCategoryImage> $categoryImageCollection */
        $categoryImageCollection = SpyCategoryImageQuery::create()
            ->useSpyCategoryImageSetToCategoryImageQuery()
                ->filterByFkCategoryImageSet($idCategoryImageSet)
                ->filterByFkCategoryImage($excludeIdCategoryImage, Criteria::NOT_IN)
            ->endUse()
            ->find();

        $categoryImageSetEntity = SpyCategoryImageSetQuery::create()
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->findOne();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageCollection($categoryImageCollection, $categoryImageSetEntity);
    }
}
