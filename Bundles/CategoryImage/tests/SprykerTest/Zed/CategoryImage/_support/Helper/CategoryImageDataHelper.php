<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImage\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryImageBuilder;
use Generated\Shared\DataBuilder\CategoryImageSetBuilder;
use Generated\Shared\DataBuilder\LocaleBuilder;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetToCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CategoryImageDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    public const IMAGE_URL_SMALL = 'image-url-small';
    public const IMAGE_URL_LARGE = 'image-url-large';
    public const IMAGE_SET_NAME = 'category-image-set';
    public const SORT_ORDER = 1;
    public const LOCALE_NAME_DE = 'de_DE';

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveCategoryImageSetForCategory(CategoryTransfer $categoryTransfer, array $seedData = [])
    {
        $categoryImageSetTransfer = $this->buildCategoryImageSetTransfer($seedData);
        $originalImageSets = $categoryTransfer->getFormImageSets();
        $newImageSet = $this->buildFormImageSets([$categoryImageSetTransfer]);
        $combinedImageSets = array_merge_recursive($originalImageSets, $newImageSet);
        $categoryTransfer->setFormImageSets($combinedImageSets);
        $this->getCategoryImageFacade()->updateCategoryImageSetCollection($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryImageSetTransfer) {
            $this->cleanupCategoryImageSet($categoryImageSetTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    public function getIdCategoryImageSetToCategoryImageForCategory(CategoryTransfer $categoryTransfer): array
    {
        return SpyCategoryImageSetToCategoryImageQuery::create()
            ->joinSpyCategoryImageSet()
            ->useSpyCategoryImageSetQuery()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->endUse()
            ->select(SpyCategoryImageSetToCategoryImageTableMap::COL_ID_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE)
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    public function getIdCategoryImageCollectionForCategory(CategoryTransfer $categoryTransfer): array
    {
        return SpyCategoryImageQuery::create()
            ->joinSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
            ->joinSpyCategoryImageSet()
            ->useSpyCategoryImageSetQuery()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->endUse()
            ->endUse()
            ->select(SpyCategoryImageTableMap::COL_ID_CATEGORY_IMAGE)
            ->find()
            ->getData();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function buildCategoryImageSetTransfer(array $seedData = [])
    {
        $seedData = $seedData + [
                'idCategoryImageSet' => null,
                'name' => static::IMAGE_SET_NAME,
                'categoryImages' => new ArrayObject([
                    $this->buildCategoryImageTransfer($seedData),
                ]),
                'locale' => $this->buildLocaleTransfer($seedData),
            ];

        return (new CategoryImageSetBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function buildCategoryImageTransfer(array $seedData = [])
    {
        $seedData = $seedData + [
            'idCategoryImage' => null,
            'externalUrlSmall' => static::IMAGE_URL_SMALL,
            'externalUrlLarge' => static::IMAGE_URL_LARGE,
            'sortOrder' => static::SORT_ORDER,

        ];
        return (new CategoryImageBuilder($seedData))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $imageSets
     *
     * @return array
     */
    public function buildFormImageSets(array $imageSets): array
    {
        $formImageSets = [];
        foreach ($imageSets as $imageSet) {
            $formImageSets[$imageSet->getLocale()->getLocaleName()][] = $imageSet;
        }

        return $formImageSets;
    }

    /**
     * @param array $formImageSets
     *
     * @return array
     */
    public function flattenFormImageSets(array $formImageSets): array
    {
        $imageSetCollection = [];
        foreach ($formImageSets as $localeName => $imageSets) {
            $imageSetCollection = array_merge($imageSetCollection, $imageSets);
        }

        return $imageSetCollection;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function buildLocaleTransfer(array $seedData = [])
    {
        $seedData = $seedData + [
            'localeName' => static::LOCALE_NAME_DE,
        ];
        return (new LocaleBuilder($seedData))->build();
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface
     */
    protected function getCategoryImageFacade(): CategoryImageFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->categoryImage()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    private function cleanupCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): void
    {
        $idCategoryImageCollection = [];
        foreach ($categoryImageSetTransfer->getCategoryImages() as $categoryImageTransfer) {
            $idCategoryImageCollection[] = $categoryImageTransfer->getIdCategoryImage();
        }

        SpyCategoryImageSetToCategoryImageQuery::create()
            ->filterByFkCategoryImageSet($categoryImageSetTransfer->getIdCategoryImageSet())
            ->filterByFkCategoryImage_In($idCategoryImageCollection)
            ->delete();

        SpyCategoryImageQuery::create()
            ->filterByIdCategoryImage($idCategoryImageCollection)
            ->delete();

        SpyCategoryImageSetQuery::create()
            ->filterByIdCategoryImageSet($categoryImageSetTransfer->getIdCategoryImageSet())
            ->findOne()
            ->delete();
    }
}
