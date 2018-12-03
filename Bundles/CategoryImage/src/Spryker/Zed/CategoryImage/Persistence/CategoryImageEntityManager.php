<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageEntityManager extends AbstractEntityManager implements CategoryImageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        $categoryImageEntity = SpyCategoryImageQuery::create()
            ->filterByIdCategoryImage(
                $categoryImageTransfer->getIdCategoryImage()
            )
            ->findOneOrCreate();

        $categoryImageEntity = $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageToEntity($categoryImageEntity, $categoryImageTransfer);

        $categoryImageEntity->save();

        $categoryImageTransfer->setIdCategoryImage($categoryImageEntity->getIdCategoryImage());

        return $categoryImageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        $categoryImageSetEntity = SpyCategoryImageSetQuery::create()
            ->filterByIdCategoryImageSet(
                $categoryImageSetTransfer->getIdCategoryImageSet()
            )
            ->findOneOrCreate();

        $categoryImageSetEntity = $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageSetToEntity($categoryImageSetEntity, $categoryImageSetTransfer);

        $categoryImageSetEntity->save();

        $categoryImageSetTransfer->setIdCategoryImageSet(
            $categoryImageSetEntity->getIdCategoryImageSet()
        );

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSet): void
    {
        $categoryImageSetEntity = SpyCategoryImageSetQuery::create()
            ->filterByIdCategoryImageSet(
                $categoryImageSet->getIdCategoryImageSet()
            )
            ->findOne();

        if ($categoryImageSetEntity) {
            foreach ($categoryImageSetEntity->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImage) {
                $this->deleteCategoryImageSetToCategoryImageEntity($categoryImageSetToCategoryImage);
            }

            $categoryImageSetEntity->delete();
        }
    }

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     * @param int|null $sortOrder
     *
     * @return int
     */
    public function saveCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage, $sortOrder = null): int
    {
        $categoryImageRelationEntity = SpyCategoryImageSetToCategoryImageQuery::create()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->findOneOrCreate();

        $categoryImageRelationEntity->setSortOrder((int)$sortOrder);
        $categoryImageRelationEntity->save();

        return $categoryImageRelationEntity->getIdCategoryImageSetToCategoryImage();
    }

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     *
     * @return void
     */
    public function deleteCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage): void
    {
        $categoryImageSetToCategoryImageEntity = SpyCategoryImageSetToCategoryImageQuery::create()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->joinWithSpyCategoryImage()
            ->findOne();

        if ($categoryImageSetToCategoryImageEntity) {
            $this->deleteCategoryImageSetToCategoryImageEntity($categoryImageSetToCategoryImageEntity);
        }
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity
     *
     * @return void
     */
    protected function deleteCategoryImageSetToCategoryImageEntity(
        SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity
    ): void {
        $categoryImage = $categoryImageSetToCategoryImageEntity->getSpyCategoryImage();
        $categoryImage->removeSpyCategoryImageSetToCategoryImage($categoryImageSetToCategoryImageEntity);
        $categoryImageSetToCategoryImageEntity->delete();

        $this->deleteOrphanCategoryImage($categoryImage);
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImage
     *
     * @return void
     */
    protected function deleteOrphanCategoryImage(SpyCategoryImage $categoryImage): void
    {
        if ($categoryImage->getSpyCategoryImageSetToCategoryImages()->isEmpty()) {
            $categoryImage->delete();
        }
    }
}
