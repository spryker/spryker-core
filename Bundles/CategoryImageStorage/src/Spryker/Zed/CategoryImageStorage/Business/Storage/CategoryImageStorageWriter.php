<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryEntityImageStorageTransfer;
use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface;
use Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface;

class CategoryImageStorageWriter implements CategoryImageStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface
     */
    protected $categoryImageFacade;

    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface
     */
    private $entityManager;

    /**
     * @param \Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface $categoryImageFacade
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface $repository
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        CategoryImageStorageToCategoryImageInterface $categoryImageFacade,
        CategoryImageStorageRepositoryInterface $repository,
        CategoryImageStorageEntityManagerInterface $entityManager
    ) {
        $this->categoryImageFacade = $categoryImageFacade;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
    {
        $imageSets = $this->getImageSetsIndexedByCategoryIdAndLocale(
            $this->repository->findCategoryImageSetsByFkCategoryIn($categoryIds)
        );

        $spyCategoryImageStorageEntities = $this->findCategoryImageStorageTransfersByCategoryIds($categoryIds);
        $this->storeData($imageSets, $spyCategoryImageStorageEntities);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds)
    {
        $spyCategoryImageStorageEntities = $this->repository->findCategoryImageStorageByIds($categoryIds);
        foreach ($spyCategoryImageStorageEntities as $spyCategoryImageStorageEntity) {
            $this->entityManager->deleteCategoryImageStorage(
                $spyCategoryImageStorageEntity->getIdCategoryImageStorage()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[] $categoryImageSets
     *
     * @return array
     */
    protected function getImageSetsIndexedByCategoryIdAndLocale(array $categoryImageSets): array
    {
        $indexedCategoryImageSets = [];
        foreach ($categoryImageSets as $categoryImageSet) {
            if ($categoryImageSet->getFkCategory()) {
                $indexedCategoryImageSets[$categoryImageSet->getFkCategory()][$categoryImageSet->getSpyLocale()->getLocaleName()][] = $categoryImageSet;
            }
        }

        return $indexedCategoryImageSets;
    }

    /**
     * @param array $imagesSets
     * @param array $spyCategoryImageStorageEntities
     *
     * @return void
     */
    protected function storeData(array $imagesSets, array $spyCategoryImageStorageEntities)
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
     * @param array $imageSets
     *
     * @return void
     */
    protected function storeDataSet(
        SpyCategoryImageStorageEntityTransfer $spyCategoryImageStorage,
        array $imageSets
    ) {
        $categoryStorageTransfer = new CategoryEntityImageStorageTransfer();
        $categoryStorageTransfer->setIdCategory($spyCategoryImageStorage->getFkCategory());
        $categoryStorageTransfer->setImageSets(new ArrayObject($imageSets));

        $spyCategoryImageStorage->setData(json_encode($categoryStorageTransfer->toArray()));
        $spyCategoryImageStorage->setKey('category_images');
        $this->entityManager->saveCategoryImageStorage($spyCategoryImageStorage);
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function findCategoryImageStorageTransfersByCategoryIds(array $categoryIds)
    {
        $categoryImageStorageTransfers = $this->repository->findCategoryImageStorageByIds($categoryIds);
        $categoryStorageEntitiesByIdAndLocale = [];

        foreach ($categoryImageStorageTransfers as $categoryImageStorageTransfer) {
            $categoryStorageEntitiesByIdAndLocale[$categoryImageStorageTransfer->getFkCategory()][$categoryImageStorageTransfer->getLocale()] = $categoryImageStorageTransfer;
        }

        return $categoryStorageEntitiesByIdAndLocale;
    }

    /**
     * @param array $spyCategoryImageStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $spyCategoryImageStorageEntities)
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
