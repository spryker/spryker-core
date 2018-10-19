<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryEntityImageStorageTransfer;
use Generated\Shared\Transfer\CategoryImageSetStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface;
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
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface $categoryImageFacade
     * @param \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface $repository
     * @param bool $isSendingToQueue
     */
    public function __construct(CategoryImageStorageToCategoryImageInterface $categoryImageFacade, CategoryImageStorageRepositoryInterface $repository, bool $isSendingToQueue)
    {
        $this->categoryImageFacade = $categoryImageFacade;
        $this->repository = $repository;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
    {
        $spyCategoryLocalizedEntities = $this->findCategoryLocalizedEntities($categoryIds);
        $imageSets = [];
        $categoryImageSetsBulk = $this->getImageSetsIndexedByCategoryIdAndLocale(
            $this->repository->findCategoryImageSetsByFkCategoryIn($categoryIds)
        );

        foreach ($spyCategoryLocalizedEntities as $spyCategoryLocalizedEntity) {
            $idCategory = $spyCategoryLocalizedEntity->getFkCategory();
            if (!isset($categoryImageSetsBulk[$idCategory])) {
                continue;
            }

            $imageSets[$idCategory][$spyCategoryLocalizedEntity->getIdCategoryAttribute()] = $this->generateCategoryImageSets(
                $categoryImageSetsBulk[$spyCategoryLocalizedEntity->getFkCategory()][$spyCategoryLocalizedEntity->getFkLocale()]
            );
        }

        $spyCategoryImageStorageEntities = $this->findCategoryImageStorageEntitiesByCategoryIds($categoryIds);
        $this->storeData($spyCategoryLocalizedEntities, $spyCategoryImageStorageEntities, $imageSets);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds)
    {
        $spyCategoryImageStorageEntities = $this->findCategoryImageStorageEntitiesByCategoryIds($categoryIds);
        foreach ($spyCategoryImageStorageEntities as $spyCategoryImageStorageLocalizedEntities) {
            /** @var \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage $spyCategoryImageStorageLocalizedEntity */
            foreach ($spyCategoryImageStorageLocalizedEntities as $spyCategoryImageStorageLocalizedEntity) {
                $spyCategoryImageStorageLocalizedEntity->delete();
            }
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
                $indexedCategoryImageSets[$categoryImageSet->getFkCategory()][$categoryImageSet->getFkLocale()][] = $categoryImageSet;
            }
        }

        return $indexedCategoryImageSets;
    }

    /**
     * @param array $spyCategoryLocalizedEntities
     * @param array $spyCategoryImageStorageEntities
     * @param array $imagesSets
     *
     * @return void
     */
    protected function storeData(array $spyCategoryLocalizedEntities, array $spyCategoryImageStorageEntities, array $imagesSets)
    {
        foreach ($spyCategoryLocalizedEntities as $spyCategoryLocalizedEntity) {
            $idCategory = $spyCategoryLocalizedEntity->getFkCategory();
            $localeName = $spyCategoryLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyCategoryImageStorageEntities[$idCategory][$localeName])) {
                $this->storeDataSet($spyCategoryLocalizedEntity, $imagesSets, $spyCategoryImageStorageEntities[$idCategory][$localeName]);

                continue;
            }

            $this->storeDataSet($spyCategoryLocalizedEntity, $imagesSets);
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $spyCategoryLocalizedEntity
     * @param array $imageSets
     * @param \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage|null $spyCategoryImageStorage
     *
     * @return void
     */
    protected function storeDataSet(SpyCategoryAttribute $spyCategoryLocalizedEntity, array $imageSets, ?SpyCategoryImageStorage $spyCategoryImageStorage = null)
    {
        if ($spyCategoryImageStorage === null) {
            $spyCategoryImageStorage = new SpyCategoryImageStorage();
        }

        if (empty($imageSets[$spyCategoryLocalizedEntity->getFkCategory()])) {
            if (!$spyCategoryImageStorage->isNew()) {
                $spyCategoryImageStorage->delete();
            }

            return;
        }

        $categoryStorageTransfer = new CategoryEntityImageStorageTransfer();
        $categoryStorageTransfer->setIdCategory($spyCategoryLocalizedEntity->getFkCategory());
        $categoryStorageTransfer->setImageSets($imageSets[$spyCategoryLocalizedEntity->getFkCategory()][$spyCategoryLocalizedEntity->getIdCategoryAttribute()]);

        $spyCategoryImageStorage->setFkCategory($spyCategoryLocalizedEntity->getFkCategory());
        $spyCategoryImageStorage->setData($categoryStorageTransfer->toArray());
        $spyCategoryImageStorage->setLocale($spyCategoryLocalizedEntity->getLocale()->getLocaleName());
        $spyCategoryImageStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyCategoryImageStorage->setKey('category_images');
        $spyCategoryImageStorage->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[] $categoryImageSetEntityTransfers
     *
     * @return \ArrayObject
     */
    protected function generateCategoryImageSets(array $categoryImageSetEntityTransfers)
    {
        $imageSets = new ArrayObject();

        foreach ($categoryImageSetEntityTransfers as $categoryImageSetEntityTransfer) {
            $imageSet = (new CategoryImageSetStorageTransfer())
                ->setName($categoryImageSetEntityTransfer->getName());
            foreach ($categoryImageSetEntityTransfer->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImageTransfer) {
                $categoryImageTransfer = $categoryImageSetToCategoryImageTransfer->getSpyCategoryImage();

                $imageSet->addImage((new CategoryImageStorageTransfer())
                    ->setIdCategoryImage($categoryImageTransfer->getIdCategoryImage())
                    ->setExternalUrlLarge($categoryImageTransfer->getExternalUrlLarge())
                    ->setExternalUrlSmall($categoryImageTransfer->getExternalUrlSmall()));
            }
            $imageSets[] = $imageSet;
        }

        return $imageSets;
    }

    /**
     * @param array $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute[]
     */
    protected function findCategoryLocalizedEntities(array $categoryIds)
    {
        return $this->repository->findCategoryAttributesByIds($categoryIds)->getData();
    }

    /**
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage[][]
     */
    protected function findCategoryImageStorageEntitiesByCategoryIds(array $categoryIds)
    {
        $categoryStorageEntities = $this->repository->findCategoryImageStorageByIds($categoryIds);
        $categoryStorageEntitiesByIdAndLocale = [];

        foreach ($categoryStorageEntities as $categoryStorageEntity) {
            $categoryStorageEntitiesByIdAndLocale[$categoryStorageEntity->getFkCategory()][$categoryStorageEntity->getLocale()] = $categoryStorageEntity;
        }

        return $categoryStorageEntitiesByIdAndLocale;
    }
}
