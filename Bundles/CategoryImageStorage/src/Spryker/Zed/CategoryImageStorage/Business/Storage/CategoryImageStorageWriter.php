<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface;

class CategoryImageStorageWriter implements CategoryImageStorageWriterInterface
{
    protected const STORAGE_KEY = 'category_images';

    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface $repository
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface $entityManager
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CategoryImageStorageRepositoryInterface $repository,
        CategoryImageStorageEntityManagerInterface $entityManager,
        bool $isSendingToQueue
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds): void
    {
        $indexedCategoryImageSetTransfers = $this->getImageSetsIndexedByCategoryIdAndLocale(
            $this->repository->getCategoryImageSetsByFkCategoryIn($categoryIds)
        );

        $indexedCategoryImageStorageItemTransfers = $this->findCategoryImageStorageItemTransfersByCategoryIds($categoryIds);
        $this->storeData($indexedCategoryImageSetTransfers, $indexedCategoryImageStorageItemTransfers);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds): void
    {
        $categoryImageStorageItemTransfers = $this->repository->getCategoryImageStorageByFkCategoryIn($categoryIds);
        foreach ($categoryImageStorageItemTransfers as $categoryImageStorageItemTransfer) {
            $categoryImageStorageItemTransfer->requireIdCategoryImageStorage();
            $this->entityManager->deleteCategoryImageStorage(
                $categoryImageStorageItemTransfer->getIdCategoryImageStorage()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[][][]
     */
    protected function getImageSetsIndexedByCategoryIdAndLocale(array $categoryImageSetTransfers): array
    {
        $indexedCategoryImageSets = [];

        foreach ($categoryImageSetTransfers as $categoryImageSetTransfer) {
            if ($categoryImageSetTransfer->getIdCategory() && $categoryImageSetTransfer->getLocale()) {
                $indexedCategoryImageSets[$categoryImageSetTransfer->getIdCategory()][$categoryImageSetTransfer->getLocale()->getLocaleName()][] = $categoryImageSetTransfer;
            }
        }

        return $indexedCategoryImageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[][][] $indexedCategoryImageSetTransfers
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[][] $indexedCategoryImageStorageItemTransfers
     *
     * @return void
     */
    protected function storeData(array $indexedCategoryImageSetTransfers, array $indexedCategoryImageStorageItemTransfers): void
    {
        foreach ($indexedCategoryImageSetTransfers as $idCategory => $categorizedImageSetTransfers) {
            foreach ($categorizedImageSetTransfers as $localeName => $localizedImageSetTransfers) {
                $categoryImageStorageItemTransfer = $this->getStorageEntityTransfer(
                    $indexedCategoryImageStorageItemTransfers,
                    $idCategory,
                    $localeName
                );
                unset($indexedCategoryImageStorageItemTransfers[$idCategory][$localeName]);
                $this->storeDataSet($categoryImageStorageItemTransfer, $localizedImageSetTransfers);
            }
        }

        $this->deleteStorageEntities($indexedCategoryImageStorageItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[][] $indexedCategoryImageStorageItemTransfers
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemTransfer
     */
    protected function getStorageEntityTransfer(
        array $indexedCategoryImageStorageItemTransfers,
        int $idCategory,
        string $localeName
    ): CategoryImageStorageItemTransfer {
        if (isset($indexedCategoryImageStorageItemTransfers[$idCategory][$localeName])) {
            return $indexedCategoryImageStorageItemTransfers[$idCategory][$localeName];
        }

        return (new CategoryImageStorageItemTransfer())
            ->setFkCategory($idCategory)
            ->setLocale($localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $localizedImageSetTransfers
     *
     * @return void
     */
    protected function storeDataSet(
        CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer,
        array $localizedImageSetTransfers
    ): void {
        $categoryImageStorageItemDataTransfer = new CategoryImageStorageItemDataTransfer();
        $categoryImageStorageItemDataTransfer->setIdCategory($categoryImageStorageItemTransfer->getFkCategory());
        $categoryImageStorageItemDataTransfer->setImageSets(new ArrayObject($localizedImageSetTransfers));

        $categoryImageStorageItemTransfer->setData($categoryImageStorageItemDataTransfer);
        $categoryImageStorageItemTransfer->setKey(static::STORAGE_KEY);
        $categoryImageStorageItemTransfer->setIsSendingToQueue($this->isSendingToQueue);
        $this->entityManager->saveCategoryImageStorage($categoryImageStorageItemTransfer);
    }

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[][]
     */
    protected function findCategoryImageStorageItemTransfersByCategoryIds(array $categoryIds): array
    {
        $categoryImageStorageItemTransfers = $this->repository->getCategoryImageStorageByFkCategoryIn($categoryIds);
        $indexedCategoryImageStorageItemTransfers = [];

        foreach ($categoryImageStorageItemTransfers as $categoryImageStorageItemTransfer) {
            $indexedCategoryImageStorageItemTransfers[$categoryImageStorageItemTransfer->getFkCategory()][$categoryImageStorageItemTransfer->getLocale()] = $categoryImageStorageItemTransfer;
        }

        return $indexedCategoryImageStorageItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[][] $categoryImageStorageItemTransfers
     *
     * @return void
     */
    protected function deleteStorageEntities(array $categoryImageStorageItemTransfers): void
    {
        foreach ($categoryImageStorageItemTransfers as $localizedCategoryImageStorageItemTransfers) {
            foreach ($localizedCategoryImageStorageItemTransfers as $localizedCategoryImageStorageItemTransfer) {
                $this->entityManager->deleteCategoryImageStorage(
                    $localizedCategoryImageStorageItemTransfer->requireIdCategoryImageStorage()
                        ->getIdCategoryImageStorage()
                );
            }
        }
    }
}
