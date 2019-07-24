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
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Locale\Helper\LocaleDataHelper;

class CategoryImageDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    public const NAMESPACE_ROOT = '\\';

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function haveCategoryImageSetForCategory(CategoryTransfer $categoryTransfer, array $seedData = []): CategoryImageSetTransfer
    {
        $seedData = $seedData + [
                CategoryImageSetTransfer::ID_CATEGORY => $categoryTransfer->getIdCategory(),
            ];
        $categoryImageSetTransfer = $this->buildCategoryImageSetTransfer($seedData);
        $categoryTransfer->addImageSet($categoryImageSetTransfer);
        $this->getCategoryImageFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryImageSetTransfer) {
            $this->cleanupCategoryImageSet($categoryImageSetTransfer);
        });

        return $categoryImageSetTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function buildCategoryImageSetTransfer(array $seedData = [])
    {
        $seedData = $seedData + [
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
        return (new CategoryImageBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function buildLocaleTransfer(array $seedData = [])
    {
        return $this->getModule(static::NAMESPACE_ROOT . LocaleDataHelper::class)->haveLocale($seedData);
    }

    /**
     * @param int[] $sortOrders
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function createCategoryImageSetWithOrderedImages(array $sortOrders): CategoryImageSetTransfer
    {
        $categoryImages = new ArrayObject();
        foreach ($sortOrders as $sortOrder) {
            $categoryImages->append($this->buildCategoryImageTransfer([CategoryImageTransfer::SORT_ORDER => $sortOrder]));
        }

        $categoryImageSetTransfer = $this->buildCategoryImageSetTransfer()->setCategoryImages($categoryImages);

        return $categoryImageSetTransfer;
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
    protected function cleanupCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): void
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
            ->filterByIdCategoryImage_In($idCategoryImageCollection)
            ->delete();

        SpyCategoryImageSetQuery::create()
            ->filterByIdCategoryImageSet($categoryImageSetTransfer->getIdCategoryImageSet())
            ->findOne()
            ->delete();
    }
}
