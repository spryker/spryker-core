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
     * {@inheritdoc}
     */
    public function saveCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        $categoryImageEntity = $this->getFactory()
            ->createCategoryImageQuery()
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
     * {@inheritdoc}
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        $categoryImageSetEntity = $this->getFactory()
            ->createCategoryImageSetQuery()
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
     * {@inheritdoc}
     */
    public function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSet): void
    {
        $categoryImageSetEntity = $this->getFactory()
            ->createCategoryImageSetQuery()
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
     * {@inheritdoc}
     */
    public function saveCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage, $sortOrder = null): int
    {
        $categoryImageRelationEntity = $this->getFactory()
            ->createCategoryImageSetToCategoryImageQuery()
            ->filterByFkCategoryImageSet($idCategoryImageSet)
            ->filterByFkCategoryImage($idCategoryImage)
            ->findOneOrCreate();

        $categoryImageRelationEntity->setSortOrder((int)$sortOrder);
        $categoryImageRelationEntity->save();

        return $categoryImageRelationEntity->getIdCategoryImageSetToCategoryImage();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage): void
    {
        $categoryImageSetToCategoryImageEntity = $this->getFactory()
            ->createCategoryImageSetToCategoryImageQuery()
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
