<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
class CategoryImageEntityManager extends AbstractEntityManager implements CategoryImageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        $categoryImageSetEntity = $this->getFactory()
            ->getCategoryImageSetQuery()
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

        foreach ($categoryImageSetTransfer->getCategoryImages() as $categoryImageTransfer) {
            $this->saveCategoryImage($categoryImageTransfer, $categoryImageSetTransfer->getIdCategoryImageSet());
        }

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveCategoryImage(
        CategoryImageTransfer $categoryImageTransfer,
        int $idCategoryImageSet
    ): CategoryImageTransfer {
        $categoryImageEntity = $this->getFactory()
            ->getCategoryImageQuery()
            ->filterByIdCategoryImage(
                $categoryImageTransfer->getIdCategoryImage()
            )
            ->findOneOrCreate();

        $categoryImageEntity = $this->getFactory()
            ->createCategoryImageMapper()
            ->mapCategoryImageToEntity($categoryImageEntity, $categoryImageTransfer);

        $categoryImageEntity->save();
        $categoryImageTransfer->setIdCategoryImage($categoryImageEntity->getIdCategoryImage());
        $this->saveCategoryImageToImageSet(
            $categoryImageTransfer->getIdCategoryImage(),
            $idCategoryImageSet,
            $categoryImageTransfer->getSortOrder()
        );

        return $categoryImageTransfer;
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageSetById(int $idCategoryImageSet): void
    {
        $categoryImageSetEntity = $this->getFactory()
            ->getCategoryImageSetQuery()
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->findOne();

        if ($categoryImageSetEntity) {
            foreach ($categoryImageSetEntity->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImage) {
                $this->deleteCategoryImageSetToCategoryImageRelation($categoryImageSetToCategoryImage);
            }

            $categoryImageSetEntity->delete();
        }
    }

    /**
     * @param int $idCategoryImage
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageFromImageSetById(int $idCategoryImage, int $idCategoryImageSet): void
    {
        $categoryImageSetToCategoryImageEntity = $this->getFactory()
            ->getCategoryImageSetToCategoryImageQuery()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->joinWithSpyCategoryImage()
            ->findOne();

        if ($categoryImageSetToCategoryImageEntity) {
            $this->deleteCategoryImageSetToCategoryImageRelation($categoryImageSetToCategoryImageEntity);
        }
    }

    /**
     * @param int $idCategoryImage
     * @param int $idCategoryImageSet
     * @param int|null $sortOrder
     *
     * @return void
     */
    protected function saveCategoryImageToImageSet(int $idCategoryImage, int $idCategoryImageSet, $sortOrder = null): void
    {
        $categoryImageRelationEntity = $this->getFactory()
            ->getCategoryImageSetToCategoryImageQuery()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->findOneOrCreate();

        $categoryImageRelationEntity->setSortOrder((int)$sortOrder);
        $categoryImageRelationEntity->save();
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity
     *
     * @return void
     */
    protected function deleteCategoryImageSetToCategoryImageRelation(
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
