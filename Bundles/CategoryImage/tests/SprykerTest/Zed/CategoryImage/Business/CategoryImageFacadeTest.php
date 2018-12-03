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
    public const LOCALE_DE = 'de_DE';
    public const MAX_CATEGORY_IMAGE_SET_COLLECTION_SIZE = 5;
    public const DUMMY_CATEGORY_IMAGE_SET_NAME = 'category-image-set-name';
    public const DUMMY_EXTERNAL_URL_SMALL = 'external-url-small';
    public const DUMMY_EXTERNAL_URL_LARGE = 'external-url-large';

    /**
     * @return void
     */
    public function testFindCategoryImagesSetCollectionByCategoryId(): void
    {
        $categoryImageSetTransferCollection = $this->buildCategoryImageSetTransferCollection(
            rand(1, static::MAX_CATEGORY_IMAGE_SET_COLLECTION_SIZE)
        );
        $categoryTransfer = $this->tester->haveCategory();
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetTransferCollection));

        $this->getFacade()->createCategoryImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImagesSetsByCategoryId(
            $categoryTransfer->getIdCategory()
        );

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
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(static::DEFAULT_CATEGORY_IMAGE_SET_COUNT);
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));

        $resultCategoryTransfer = $this->getFacade()->createCategoryImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImagesSetsByCategoryId(
            $resultCategoryTransfer->getIdCategory()
        );

        $this->assertSame($categoryTransfer, $resultCategoryTransfer);
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
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImage = $this->tester->buildCategoryImageTransfer();
        $categoryImageSet = $this->tester->buildCategoryImageSetTransfer();
        $categoryImageSet->setCategoryImages(new ArrayObject([$categoryImage]));
        $categoryTransfer->addImageSet($categoryImageSet);

        $this->getFacade()->createCategoryImageSets($categoryTransfer);

        $categoryImage->setExternalUrlSmall(static::DUMMY_EXTERNAL_URL_SMALL);
        $categoryImage->setExternalUrlLarge(static::DUMMY_EXTERNAL_URL_LARGE);
        $categoryImageSet->setName(static::DUMMY_CATEGORY_IMAGE_SET_NAME);

        $this->getFacade()->updateCategoryImageSets($categoryTransfer);

        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImagesSetsByCategoryId(
            $categoryTransfer->getIdCategory()
        );
        $dbCategoryImageSet = $dbCategoryImageSetCollection[0];

        $this->assertEquals(static::DUMMY_CATEGORY_IMAGE_SET_NAME, $dbCategoryImageSet->getName());
        $this->assertEquals(static::DUMMY_EXTERNAL_URL_SMALL, $dbCategoryImageSet->getCategoryImages()[0]->getExternalUrlSmall());
        $this->assertEquals(static::DUMMY_EXTERNAL_URL_LARGE, $dbCategoryImageSet->getCategoryImages()[0]->getExternalUrlLarge());
    }

    /**
     * @return void
     */
    public function testDeleteCategoryImageSet(): void
    {
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(rand(2, static::MAX_CATEGORY_IMAGE_SET_COLLECTION_SIZE));
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));

        $this->getFacade()->createCategoryImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImagesSetsByCategoryId(
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
        $this->getFacade()->updateCategoryImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->getCategoryImagesSetsByCategoryId(
            $categoryTransfer->getIdCategory()
        );
        $this->assertFalse(in_array(
            $idCategoryImageSetToBeDeleted,
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection)
        ));
    }

    /**
     * @return void
     */
    public function testExpandCategoryWithImageSets(): void
    {
        $categoryTransfer = $this->tester->haveCategory();
        $categoryImageSetCollection = $this->buildCategoryImageSetTransferCollection(
            rand(1, static::MAX_CATEGORY_IMAGE_SET_COLLECTION_SIZE)
        );
        $categoryTransfer->setImageSets(new ArrayObject($categoryImageSetCollection));

        $this->getFacade()->createCategoryImageSets($categoryTransfer);
        $categoryTransfer->setImageSets(new ArrayObject());
        $this->getFacade()->expandCategoryWithImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $categoryTransfer->getImageSets()->getArrayCopy();
        $this->assertEquals(count($categoryImageSetCollection), count($dbCategoryImageSetCollection));
        $this->assertEmpty(array_diff(
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection),
            $this->getIdCategoryImageSetCollection($categoryImageSetCollection)
        ));
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
        $seedData = $seedData + [
            'localeName' => static::LOCALE_DE,
        ];

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
