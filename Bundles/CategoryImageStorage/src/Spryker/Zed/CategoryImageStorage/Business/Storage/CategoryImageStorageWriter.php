<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageSetStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageTransfer;
use Generated\Shared\Transfer\SpyCategoryImageEntityTransfer;
use Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer;
use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface;

class CategoryImageStorageWriter implements CategoryImageStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface $repository
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        CategoryImageStorageRepositoryInterface $repository,
        CategoryImageStorageEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds): void
    {
        $imageSets = $this->getImageSetsIndexedByCategoryIdAndLocale(
            $this->repository->getCategoryImageSetsByFkCategoryIn($categoryIds)
        );

        $spyCategoryImageStorageEntities = $this->findCategoryImageStorageTransfersByCategoryIds($categoryIds);
        $this->storeData($imageSets, $spyCategoryImageStorageEntities);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds): void
    {
        $spyCategoryImageStorageEntities = $this->repository->getCategoryImageStorageByFkCategoryIn($categoryIds);
        foreach ($spyCategoryImageStorageEntities as $spyCategoryImageStorageEntity) {
            $this->entityManager->deleteCategoryImageStorage(
                $spyCategoryImageStorageEntity->getIdCategoryImageStorage()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[] $categoryImageSets
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[][][]
     */
    protected function getImageSetsIndexedByCategoryIdAndLocale(array $categoryImageSets): array
    {
        $indexedCategoryImageSets = [];
        foreach ($categoryImageSets as $categoryImageSet) {
            if ($categoryImageSet->getFkCategory() && $categoryImageSet->getSpyLocale()) {
                $indexedCategoryImageSets[$categoryImageSet->getFkCategory()][$categoryImageSet->getSpyLocale()->getLocaleName()][] = $categoryImageSet;
            }
        }

        return $indexedCategoryImageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[][][] $imagesSets
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[][] $spyCategoryImageStorageEntities
     *
     * @return void
     */
    protected function storeData(array $imagesSets, array $spyCategoryImageStorageEntities): void
    {
        foreach ($imagesSets as $idCategory => $categorizedImageSets) {
            foreach ($categorizedImageSets as $localeName => $localizedImageSets) {
                $categoryImageStorageEntityTransfer = $this->getStorageEntityTransfer(
                    $spyCategoryImageStorageEntities,
                    $idCategory,
                    $localeName
                );
                unset($spyCategoryImageStorageEntities[$idCategory][$localeName]);
                $this->storeDataSet($categoryImageStorageEntityTransfer, $localizedImageSets);
            }
        }

        $this->deleteStorageEntities($spyCategoryImageStorageEntities);
    }

    /**
     * @param array $spyCategoryImageStorageEntities
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer
     */
    protected function getStorageEntityTransfer(
        array $spyCategoryImageStorageEntities,
        int $idCategory,
        string $localeName
    ): SpyCategoryImageStorageEntityTransfer {
        if (isset($spyCategoryImageStorageEntities[$idCategory][$localeName])) {
            return $spyCategoryImageStorageEntities[$idCategory][$localeName];
        }

        return (new SpyCategoryImageStorageEntityTransfer())
            ->setFkCategory($idCategory)
            ->setLocale($localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $spyCategoryImageStorage
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[] $imageSets
     *
     * @return void
     */
    protected function storeDataSet(
        SpyCategoryImageStorageEntityTransfer $spyCategoryImageStorage,
        array $imageSets
    ): void {
        $categoryStorageTransfer = new CategoryImageSetCollectionStorageTransfer();
        $categoryStorageTransfer->setIdCategory($spyCategoryImageStorage->getFkCategory());
        $categoryStorageTransfer->setImageSets(new ArrayObject(
            $this->mapSpyCategoryImageSetEntityTransferCollection($imageSets)
        ));

        $spyCategoryImageStorage->setData(json_encode($categoryStorageTransfer->toArray()));
        $spyCategoryImageStorage->setKey('category_images');
        $this->entityManager->saveCategoryImageStorage($spyCategoryImageStorage);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[] $spyCategoryImageSetTransferCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetStorageTransfer[]
     */
    protected function mapSpyCategoryImageSetEntityTransferCollection(array $spyCategoryImageSetTransferCollection): array
    {
        $categoryImageSetStorageTransferCollection = [];
        foreach ($spyCategoryImageSetTransferCollection as $spyImageSetTransfer) {
            $categoryImageSetStorageTransferCollection[] = $this->mapSpyCategoryImageSetEntityTransfer($spyImageSetTransfer);
        }

        return $categoryImageSetStorageTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer $spyCategoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetStorageTransfer
     */
    protected function mapSpyCategoryImageSetEntityTransfer(SpyCategoryImageSetEntityTransfer $spyCategoryImageSetTransfer): CategoryImageSetStorageTransfer
    {
        $categoryImageSetStorageTransfer = new CategoryImageSetStorageTransfer();
        $categoryImageSetStorageTransfer->setName(
            $spyCategoryImageSetTransfer->getName()
        );
        $categoryImageSetStorageTransfer->setImages(
            new ArrayObject($this->mapSpyCategoryImageEntityTransferCollection($spyCategoryImageSetTransfer))
        );

        return $categoryImageSetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer $spyCategoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageTransfer[]
     */
    protected function mapSpyCategoryImageEntityTransferCollection(SpyCategoryImageSetEntityTransfer $spyCategoryImageSetTransfer): array
    {
        $categoryImages = [];
        foreach ($spyCategoryImageSetTransfer->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImage) {
            $categoryImages[] = $this->mapSpyCategoryImageEntityTransfer($categoryImageSetToCategoryImage->getSpyCategoryImage());
        }

        return $categoryImages;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageEntityTransfer $spyCategoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageTransfer
     */
    protected function mapSpyCategoryImageEntityTransfer(SpyCategoryImageEntityTransfer $spyCategoryImageTransfer): CategoryImageStorageTransfer
    {
        return (new CategoryImageStorageTransfer())->fromArray($spyCategoryImageTransfer->toArray(), true);
    }

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[][]
     */
    protected function findCategoryImageStorageTransfersByCategoryIds(array $categoryIds): array
    {
        $categoryImageStorageTransfers = $this->repository->getCategoryImageStorageByFkCategoryIn($categoryIds);
        $categoryStorageEntitiesByIdAndLocale = [];

        foreach ($categoryImageStorageTransfers as $categoryImageStorageTransfer) {
            $categoryStorageEntitiesByIdAndLocale[$categoryImageStorageTransfer->getFkCategory()][$categoryImageStorageTransfer->getLocale()] = $categoryImageStorageTransfer;
        }

        return $categoryStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[][] $spyCategoryImageStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $spyCategoryImageStorageEntities): void
    {
        foreach ($spyCategoryImageStorageEntities as $localizedImageStorageEntities) {
            /** @var \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $imageStorageEntityTransfer */
            foreach ($localizedImageStorageEntities as $imageStorageEntityTransfer) {
                $this->entityManager->deleteCategoryImageStorage(
                    $imageStorageEntityTransfer->requireIdCategoryImageStorage()
                        ->getIdCategoryImageStorage()
                );
            }
        }
    }
}
