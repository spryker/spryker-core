<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImage\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryImage
 * @group Business
 * @group Facade
 * @group CategoryImageFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\CategoryImage\CategoryImageBusinessTester $tester
 */
class CategoryImageFacadeTest extends Test
{
    public const DEFAULT_CATEGORY_IMAGE_SET_COUNT = 1;
    public const MAX_CATEGORY_IMAGE_SET_COUNT = 5;

    /**
     * @return void
     */
    public function testFindCategoryImagesSetCollectionByCategoryId(): void
    {
        // Assign
        $categoryImageSetTransferCollection = $this->buildCategoryImageSetTransferCollection(
            rand(1, static::MAX_CATEGORY_IMAGE_SET_COUNT)
        );
        $categoryTransfer = $this->tester->haveCategory();
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetTransferCollection));

        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );

        // Assert
        $this->assertEquals(count($categoryImageSetTransferCollection), count($dbCategoryImageSetCollection));
        $this->assertEmpty(array_diff(
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection),
            $this->getIdCategoryImageSetCollection($categoryImageSetTransferCollection)
        ));
    }

    /**
     * @return void
     */
    public function testCreateCategoryWithOneImageSet(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(static::DEFAULT_CATEGORY_IMAGE_SET_COUNT);
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));

        // Act
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );

        // Assert
        $this->assertNotEmpty($dbCategoryImageSetCollection);
        $this->assertEquals(static::DEFAULT_CATEGORY_IMAGE_SET_COUNT, count($dbCategoryImageSetCollection));
        $this->assertEquals(
            reset($categoryImageSetCollection)->getIdCategoryImageSet(),
            reset($dbCategoryImageSetCollection)->getIdCategoryImageSet()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryImageSetCollection(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImage = $this->tester->buildCategoryImageTransfer();
        $categoryImageSet = $this->tester->buildCategoryImageSetTransfer();
        $categoryImageSet->setCategoryImages(new ArrayObject([$categoryImage]));
        $categoryTransfer->addImageSet($categoryImageSet);
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $this->getFacade()->updateCategoryImageSetsForCategory($categoryTransfer);

        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );
        $dbCategoryImageSet = $dbCategoryImageSetCollection[0];

        // Assert
        $this->assertEquals($categoryImageSet->getName(), $dbCategoryImageSet->getName());
        $this->assertEquals($categoryImage->getExternalUrlSmall(), $dbCategoryImageSet->getCategoryImages()[0]->getExternalUrlSmall());
        $this->assertEquals($categoryImage->getExternalUrlLarge(), $dbCategoryImageSet->getCategoryImages()[0]->getExternalUrlLarge());
    }

    /**
     * @return void
     */
    public function testDeleteRandomImageSetForCategory(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(rand(2, static::MAX_CATEGORY_IMAGE_SET_COUNT));
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));

        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );
        $randomImageSetKeyToBeDeleted = array_rand($categoryImageSetCollection);
        $idCategoryImageSetToBeDeleted = $categoryImageSetCollection[$randomImageSetKeyToBeDeleted]->getIdCategoryImageSet();
        $this->assertTrue(in_array(
            $idCategoryImageSetToBeDeleted,
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection)
        ));
        unset($categoryImageSetCollection[$randomImageSetKeyToBeDeleted]);
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));
        $this->getFacade()->updateCategoryImageSetsForCategory($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );

        // Assert
        $this->assertFalse(in_array(
            $idCategoryImageSetToBeDeleted,
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection)
        ));
    }

    /**
     * @return void
     */
    public function testDeleteImageSetsByCategoryId(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(rand(2, static::MAX_CATEGORY_IMAGE_SET_COUNT));
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $this->getFacade()->deleteCategoryImageSetsByIdCategory($categoryTransfer->getIdCategory());

        // Assert
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        );
        $this->assertEmpty($dbCategoryImageSetCollection);
    }

    /**
     * @return void
     */
    public function testExpandCategoryWithImageSets(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(
            rand(1, static::MAX_CATEGORY_IMAGE_SET_COUNT)
        );
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);
        $categoryTransfer->setImageSets(new ArrayObject());

        // Act
        $this->getFacade()->expandCategoryWithImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $categoryTransfer->getImageSets()->getArrayCopy();

        // Assert
        $this->assertEquals(count($categoryImageSetCollection), count($dbCategoryImageSetCollection));
        $this->assertEmpty(array_diff(
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection),
            $this->getIdCategoryImageSetCollection($categoryImageSetCollection)
        ));
    }

    /**
     * @return void
     */
    public function testGetCategoryImageSetsByIdCategorySortsImagesBySortOrderAsc(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetTransfer = $this->tester->createCategoryImageSetWithOrderedImages([3, 1, 0, 2]);
        $categoryTransfer->setImageSets(new ArrayObject([$categoryImageSetTransfer]));
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $categoryImageCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        )[0]->getCategoryImages();

        $sortOrder = 0;
        foreach ($categoryImageCollection as $categoryImageTransfer) {
            $this->assertTrue($categoryImageTransfer->getSortOrder() >= $sortOrder);
            $sortOrder = $categoryImageTransfer->getSortOrder();
        }
    }

    /**
     * @return void
     */
    public function testGetCategoryImageSetsByIdCategorySortsImagesByIdCategoryImageSetToCategoryImageAsc(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetTransfer = $this->tester->createCategoryImageSetWithOrderedImages([0, 0, 0]);
        $categoryTransfer->setImageSets(new ArrayObject([$categoryImageSetTransfer]));
        $this->getFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        // Act
        $categoryImageCollection = $this->getFacade()->getCategoryImageSetsByIdCategory(
            $categoryTransfer->getIdCategory()
        )[0]->getCategoryImages();

        $idCategoryImageSetToCategoryImage = 0;
        foreach ($categoryImageCollection as $categoryImageTransfer) {
            $this->assertTrue(
                $categoryImageTransfer->getIdCategoryImageSetToCategoryImage() > $idCategoryImageSetToCategoryImage
            );
            $idCategoryImageSetToCategoryImage = $categoryImageTransfer->getIdCategoryImageSetToCategoryImage();
        }
    }

    /**
     * @param int $size
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    protected function buildCategoryImageSetTransferCollection(int $size): array
    {
        $categoryImageTransferCollection = [];
        for ($i = 1; $i <= $size; ++$i) {
            $categoryImageTransferCollection[] = $this->tester->buildCategoryImageSetTransfer();
        }

        return $categoryImageTransferCollection;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function haveLocale(array $seedData = []): LocaleTransfer
    {
        return $this->tester->haveLocale($seedData);
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    protected function getIdCategoryImageSetCollection(array $categoryImageSetCollection): array
    {
        return array_map(function (CategoryImageSetTransfer $categoryImageSetTransfer) {
            return $categoryImageSetTransfer->getIdCategoryImageSet();
        }, $categoryImageSetCollection);
    }
}
