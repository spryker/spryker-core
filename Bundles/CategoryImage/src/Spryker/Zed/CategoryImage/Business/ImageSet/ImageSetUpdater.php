<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ImageSetUpdater implements ImageSetUpdaterInterface
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryImageSetsForCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryImageSetsForCategoryTransaction(CategoryTransfer $categoryTransfer): void
    {
        foreach ($categoryTransfer->getImageSets() as $categoryImageSetTransfer) {
            $categoryImageSetTransfer->setIdCategory(
                $categoryTransfer->requireIdCategory()->getIdCategory()
            );
            $this->categoryImageEntityManager->saveCategoryImageSet($categoryImageSetTransfer);
        }

        $this->deleteRemovedImageSets($categoryTransfer);
        $this->deleteRemovedImages($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function deleteRemovedImageSets(CategoryTransfer $categoryTransfer): void
    {
        $existingCategoryImageSetIds = [];

        foreach ($categoryTransfer->getImageSets() as $categoryImageSetTransfer) {
            $existingCategoryImageSetIds[] = $categoryImageSetTransfer->getIdCategoryImageSet();
        }

        $removedCategoryImageSets = $this->categoryImageRepository
            ->getCategoryImageSetsByIdCategory(
                $categoryTransfer->getIdCategory(),
                $existingCategoryImageSetIds
            );

        foreach ($removedCategoryImageSets as $categoryImageSetTransfer) {
            $this->categoryImageEntityManager->deleteCategoryImageSetById(
                $categoryImageSetTransfer->getIdCategoryImageSet()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function deleteRemovedImages(CategoryTransfer $categoryTransfer): void
    {
        foreach ($categoryTransfer->getImageSets() as $categoryImageSetTransfer) {
            $this->cleanupImagesForImageSet($categoryImageSetTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function cleanupImagesForImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): void
    {
        $existingCategoryImageIds = [];

        foreach ($categoryImageSetTransfer->getCategoryImages() as $categoryImageTransfer) {
            $existingCategoryImageIds[] = $categoryImageTransfer->getIdCategoryImage();
        }

        $removedCategoryImages = $this->categoryImageRepository
            ->getCategoryImagesByCategoryImageSetId(
                $categoryImageSetTransfer->getIdCategoryImageSet(),
                $existingCategoryImageIds
            );

        foreach ($removedCategoryImages as $categoryImageTransfer) {
            $this->categoryImageEntityManager
                ->deleteCategoryImageFromImageSetById(
                    $categoryImageTransfer->getIdCategoryImage(),
                    $categoryImageSetTransfer->getIdCategoryImageSet()
                );
        }
    }
}
