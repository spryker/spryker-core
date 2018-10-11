<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class Writer implements WriterInterface
{
    use TransactionTrait;
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface
     */
    protected $categoryImageEntityManager;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface $categoryImageEntityManager
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageEntityManagerInterface $categoryImageEntityManager
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->categoryImageEntityManager = $categoryImageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function createCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        return $this->saveCategoryImage($categoryImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function updateCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        return $this->saveCategoryImage($categoryImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        $categoryImageEntity = $this->categoryImageEntityManager->findOrCreateCategoryImageById(
            $categoryImageTransfer->getIdCategoryImage()
        );

        $categoryImageEntity->fromArray($categoryImageTransfer->toArray());
        $categoryImageEntity->save();

        $categoryImageTransfer->setIdCategoryImage($categoryImageEntity->getIdCategoryImage());

        return $categoryImageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    public function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): void
    {
        $categoryImageSetTransfer->requireIdCategoryImageSet();

        $categoryImageSetEntity = $this->categoryImageRepository->findImageSetById(
            $categoryImageSetTransfer->getIdCategoryImageSet()
        );

        if ($categoryImageSetEntity) {
            $this->deleteCategoryImageSetEntity($categoryImageSetEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function deleteMissingCategoryImageSetInCategory(CategoryTransfer $categoryTransfer)
    {
        $excludeIdCategoryImageSet = [];

        foreach ($categoryTransfer->getImageSets() as $categoryImageSetTransfer) {
            $excludeIdCategoryImageSet[] = $categoryImageSetTransfer->getIdCategoryImageSet();
        }

        $missingProductImageSets = $this->categoryImageRepository
            ->findCategoryImageSetsByCategoryId(
                $categoryTransfer->getIdCategory(),
                $excludeIdCategoryImageSet
            )->getArrayCopy();

        $this->deleteCategoryImageSetEntities($missingProductImageSets);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function deleteMissingProductImageInProductImageSet(CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $excludeIdCategoryImage = [];

        foreach ($categoryImageSetTransfer->getCategoryImages() as $categoryImageTransfer) {
            $excludeIdCategoryImage[] = $categoryImageTransfer->getIdCategoryImage();
        }

        $missingCategoryImageSetsToCategoryImage = $this->categoryImageRepository
            ->findCategoryImageSetsToCategoryImageByCategoryImageSetId(
                $categoryImageSetTransfer->getIdCategoryImageSet(),
                $excludeIdCategoryImage
            );

        foreach ($missingCategoryImageSetsToCategoryImage as $categoryImageSetToCategoryImage) {
            $this->deleteCategoryImageSetToCategoryImage($categoryImageSetToCategoryImage);
        }
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[] $categoryImageSets
     *
     * @return void
     */
    protected function deleteCategoryImageSetEntities(array $categoryImageSets)
    {
        foreach ($categoryImageSets as $categoryImageSet) {
            $this->deleteCategoryImageSetEntity($categoryImageSet);
        }
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSet
     *
     * @return void
     */
    protected function deleteCategoryImageSetEntity(SpyCategoryImageSet $categoryImageSet)
    {
        foreach ($categoryImageSet->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImage) {
            $this->deleteCategoryImageSetToCategoryImage($categoryImageSetToCategoryImage);
        }

        $categoryImageSet->delete();
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImage
     *
     * @return void
     */
    protected function deleteCategoryImageSetToCategoryImage(SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImage): void
    {
        $categoryImage = $categoryImageSetToCategoryImage->getSpyCategoryImage();
        $categoryImage->removeSpyCategoryImageSetToCategoryImage($categoryImageSetToCategoryImage);

        $categoryImageSetToCategoryImage->delete();

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

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function createCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        return $this->saveCategoryImageSet($categoryImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function updateCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        return $this->saveCategoryImageSet($categoryImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($categoryImageSetTransfer) {
            $this->executeSaveCategoryImageSetTransaction($categoryImageSetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function executeSaveCategoryImageSetTransaction(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        if ($categoryImageSetTransfer->getIdCategoryImageSet()) {
            $this->deleteMissingProductImageInProductImageSet($categoryImageSetTransfer);
        }

        $categoryImageSetEntity = $this->categoryImageEntityManager
            ->findOrCreateCategoryImageSetById(
                $categoryImageSetTransfer->getIdCategoryImageSet()
            );

        $categoryImageSetEntity = $this->mapCategoryImageSetEntity($categoryImageSetEntity, $categoryImageSetTransfer);
        $categoryImageSetEntity->save();

        $categoryImageSetTransfer->setIdCategoryImageSet(
            $categoryImageSetEntity->getIdCategoryImageSet()
        );

        $categoryImageSetTransfer = $this->persistCategoryImageSetCollection($categoryImageSetTransfer);

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    protected function mapCategoryImageSetEntity(SpyCategoryImageSet $categoryImageSetEntity, CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $categoryImageSetEntity->fromArray($categoryImageSetTransfer->toArray());
        $categoryImageSetEntity->setFkCategory($categoryImageSetTransfer->getIdCategory());

        if ($categoryImageSetTransfer->getLocale()) {
            $categoryImageSetEntity->setFkLocale($categoryImageSetTransfer->getLocale()->getIdLocale());
        }

        return $categoryImageSetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function persistCategoryImageSetCollection(CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        foreach ($categoryImageSetTransfer->getCategoryImages() as $imageTransfer) {
            $imageTransfer = $this->saveCategoryImage($imageTransfer);

            $this->persistCategoryImageRelation(
                $categoryImageSetTransfer->requireIdCategoryImageSet()->getIdCategoryImageSet(),
                $imageTransfer->getIdCategoryImage(),
                $imageTransfer->getSortOrder()
            );
        }

        return $categoryImageSetTransfer;
    }

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     * @param int|null $sortOrder
     *
     * @return int
     */
    public function persistCategoryImageRelation(int $idCategoryImageSet, int $idCategoryImage, $sortOrder = null)
    {
        $categoryImageRelationEntity = $this->categoryImageEntityManager
            ->findOrCreateCategoryImageRelation(
                $idCategoryImageSet,
                $idCategoryImage
            );

        $categoryImageRelationEntity->setSortOrder((int)$sortOrder);
        $categoryImageRelationEntity->save();

        return $categoryImageRelationEntity->getIdCategoryImageSetToCategoryImage();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSetCollection(CategoryTransfer $categoryTransfer)
    {
        foreach ($categoryTransfer->getImageSets() as $imageSetTransfer) {
            $imageSetTransfer->setIdCategory(
                $categoryTransfer
                    ->requireIdCategory()
                    ->getIdCategory()
            );

            $this->createCategoryImageSet($imageSetTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSetCollection(CategoryTransfer $categoryTransfer)
    {
        foreach ($categoryTransfer->getImageSets() as $imageSetTransfer) {
            $imageSetTransfer->setIdCategory(
                $categoryTransfer
                    ->requireIdCategory()
                    ->getIdCategory()
            );

            $this->updateCategoryImageSet($imageSetTransfer);
        }

        $this->deleteMissingCategoryImageSetInCategory($categoryTransfer);
        return $categoryTransfer;
    }
}
