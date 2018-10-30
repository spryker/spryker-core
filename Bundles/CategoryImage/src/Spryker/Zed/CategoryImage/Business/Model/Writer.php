<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Writer implements WriterInterface
{
    public const LOCALIZED_CATEGORY_IMAGE_SET_PREFIX = 'image_set';

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface
     */
    protected $categoryImageEntityManager;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface $categoryImageEntityManager
     * @param \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface $localeProvider
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageEntityManagerInterface $categoryImageEntityManager,
        LocaleProviderInterface $localeProvider
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->categoryImageEntityManager = $categoryImageEntityManager;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSetCollection(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $this->saveFormCategoryImageSetCollection($categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSetCollection(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $this->saveFormCategoryImageSetCollection($categoryTransfer);
        $this->deleteMissingCategoryImageSetInCategory($categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function saveFormCategoryImageSetCollection(CategoryTransfer $categoryTransfer): void
    {
        $localizedImageSetCollection = $this->localizeCategoryImageSetCollection($categoryTransfer);
        foreach ($localizedImageSetCollection as $imageSetTransfer) {
            $this->saveCategoryImageSet($imageSetTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $missingCategoryImageSetTransferCollection
     *
     * @return void
     */
    protected function deleteCategoryImageSetCollection(array $missingCategoryImageSetTransferCollection): void
    {
        foreach ($missingCategoryImageSetTransferCollection as $missingCategoryImageSetTransfer) {
            $this->deleteCategoryImageSet($missingCategoryImageSetTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): void
    {
        $this->categoryImageEntityManager->deleteCategoryImageSet(
            $categoryImageSetTransfer->requireIdCategoryImageSet()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        if ($categoryImageSetTransfer->getIdCategoryImageSet()) {
            $this->deleteMissingCategoryImageSetToCategoryImage($categoryImageSetTransfer);
        }

        $categoryImageSetTransfer = $this->categoryImageEntityManager->saveCategoryImageSet($categoryImageSetTransfer);
        $categoryImageSetTransfer = $this->saveCategoryImageCollection($categoryImageSetTransfer);

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function deleteMissingCategoryImageSetToCategoryImage(CategoryImageSetTransfer $categoryImageSetTransfer): void
    {
        $excludeIdCategoryImageCollection = [];

        foreach ($categoryImageSetTransfer->getCategoryImages() as $categoryImageTransfer) {
            $excludeIdCategoryImageCollection[] = $categoryImageTransfer->getIdCategoryImage();
        }

        $missingCategoryImageCollection = $this->categoryImageRepository
            ->findCategoryImagesByCategoryImageSetId(
                $categoryImageSetTransfer->getIdCategoryImageSet(),
                $excludeIdCategoryImageCollection
            );

        foreach ($missingCategoryImageCollection as $missingCategoryImage) {
            $this->categoryImageEntityManager
                ->deleteCategoryImageSetToCategoryImage(
                    $categoryImageSetTransfer->getIdCategoryImageSet(),
                    $missingCategoryImage->getIdCategoryImage()
                );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function saveCategoryImageCollection(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        foreach ($categoryImageSetTransfer->getCategoryImages() as $imageTransfer) {
            $imageTransfer = $this->saveCategoryImage($imageTransfer);

            $this->categoryImageEntityManager->saveCategoryImageSetToCategoryImage(
                $categoryImageSetTransfer->getIdCategoryImageSet(),
                $imageTransfer->getIdCategoryImage(),
                $imageTransfer->getSortOrder()
            );
        }

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    protected function saveCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer
    {
        return $this->categoryImageEntityManager->saveCategoryImage($categoryImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function deleteMissingCategoryImageSetInCategory(CategoryTransfer $categoryTransfer): void
    {
        $excludeIdCategoryImageSet = [];

        foreach ($categoryTransfer->getFormImageSets() as $localizedImageSetCollection) {
            $localizedIdCategoryImageSet = array_map(
                function (CategoryImageSetTransfer $categoryImageSetTransfer) {
                    return $categoryImageSetTransfer->getIdCategoryImageSet();
                },
                $localizedImageSetCollection
            );
            $excludeIdCategoryImageSet = array_merge(
                $excludeIdCategoryImageSet,
                array_filter($localizedIdCategoryImageSet)
            );
        }

        $missingCategoryImageSetCollection = $this->categoryImageRepository
            ->findCategoryImageSetsByCategoryId(
                $categoryTransfer->getIdCategory(),
                $excludeIdCategoryImageSet
            );

        $this->deleteCategoryImageSetCollection($missingCategoryImageSetCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function localizeCategoryImageSetCollection(CategoryTransfer $categoryTransfer): array
    {
        $localeCollection = $this->localeProvider->getLocaleCollection(true);
        $localizedImageSetCollection = [];

        foreach ($localeCollection as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $imageSetTransferCollection = $categoryTransfer->getFormImageSets()[$localeName] ?? [];

            /** @var \Generated\Shared\Transfer\CategoryImageSetTransfer $imageSetTransfer */
            foreach ($imageSetTransferCollection as $imageSetTransfer) {
                $imageSetTransfer->setIdCategory(
                    $categoryTransfer->requireIdCategory()->getIdCategory()
                );
                $imageSetTransfer->setLocale($localeTransfer);
                $localizedImageSetCollection[] = $imageSetTransfer;
            }
        }

        return $localizedImageSetCollection;
    }
}
