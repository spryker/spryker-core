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
     * @param int $idCategory
     * @param array $excludeIdCategoryImageSets
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImageSetsByCategoryId(int $idCategory, array $excludeIdCategoryImageSets = []): array
    {
        $categoryImageSetEntityCollection = $this->getFactory()
            ->createCategoryImageSetQuery()
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
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
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
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->findOne();

        return $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageCollection($categoryImageCollection, $categoryImageSetEntity);
    }
}
