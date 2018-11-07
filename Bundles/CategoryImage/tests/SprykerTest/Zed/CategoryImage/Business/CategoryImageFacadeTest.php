<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImage\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CategoryImageBuilder;
use Generated\Shared\DataBuilder\CategoryImageSetBuilder;
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
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets($categoryImageSetTransferCollection)
        );

        $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->findCategoryImagesSetCollectionByCategoryId(
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
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets($categoryImageSetCollection)
        );

        $resultCategoryTransfer = $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->findCategoryImagesSetCollectionByCategoryId(
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
        $categoryImage = $this->buildCategoryImageTransfer();
        $categoryImageSet = $this->buildCategoryImageSetTransfer();
        $categoryImageSet->setCategoryImages(new ArrayObject([$categoryImage]));
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets([$categoryImageSet])
        );

        $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);

        $categoryImage->setExternalUrlSmall(static::DUMMY_EXTERNAL_URL_SMALL);
        $categoryImage->setExternalUrlLarge(static::DUMMY_EXTERNAL_URL_LARGE);
        $categoryImageSet->setName(static::DUMMY_CATEGORY_IMAGE_SET_NAME);

        $this->getFacade()->updateCategoryImageSetCollection($categoryTransfer);

        $dbCategoryImageSetCollection = $this->getFacade()->findCategoryImagesSetCollectionByCategoryId(
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
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets($categoryImageSetCollection)
        );

        $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->findCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->getIdCategory()
        );
        $randomImageSetKeyToBeDeleted = array_rand($categoryImageSetCollection);
        $idCategoryImageSetToBeDeleted = $categoryImageSetCollection[$randomImageSetKeyToBeDeleted]->getIdCategoryImageSet();
        $this->assertTrue(in_array(
            $idCategoryImageSetToBeDeleted,
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection)
        ));
        unset($categoryImageSetCollection[$randomImageSetKeyToBeDeleted]);
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets($categoryImageSetCollection)
        );
        $this->getFacade()->updateCategoryImageSetCollection($categoryTransfer);
        $dbCategoryImageSetCollection = $this->getFacade()->findCategoryImagesSetCollectionByCategoryId(
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
        $categoryTransfer->setFormImageSets(
            $this->buildFormImageSets($categoryImageSetCollection)
        );

        $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);
        $categoryTransfer->setFormImageSets([]);
        $this->getFacade()->expandCategoryWithImageSets($categoryTransfer);
        $dbCategoryImageSetCollection = $this->flattenFormImageSets($categoryTransfer->getFormImageSets());
        $this->assertEquals(count($categoryImageSetCollection), count($dbCategoryImageSetCollection));
        $this->assertEmpty(array_diff(
            $this->getIdCategoryImageSetCollection($dbCategoryImageSetCollection),
            $this->getIdCategoryImageSetCollection($categoryImageSetCollection)
        ));
    }

    /**
     * @param int $size
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function buildCategoryImageSetTransferCollection(int $size): array
    {
        $categoryImageTransferCollection = [];
        for ($i = 1; $i <= $size; ++$i) {
            $categoryImageTransferCollection[] = $this->buildCategoryImageSetTransfer();
        }

        return $categoryImageTransferCollection;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function buildCategoryImageSetTransfer(array $seedData = [])
    {
        $categoryImageTransfer = $this->buildCategoryImageTransfer();
        $seedData = $seedData + [
                'idCategoryImageSet' => null,
                'locale' => $this->haveLocale(),
                'categoryImages' => new ArrayObject([
                    $categoryImageTransfer,
                ]),
            ];

        return (new CategoryImageSetBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function buildCategoryImageTransfer($seedData = [])
    {
        return (new CategoryImageBuilder($seedData))->build();
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
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $imageSets
     *
     * @return array
     */
    protected function buildFormImageSets(array $imageSets): array
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
    protected function flattenFormImageSets(array $formImageSets): array
    {
        $imageSetCollection = [];
        foreach ($formImageSets as $localeName => $imageSets) {
            $imageSetCollection = array_merge($imageSetCollection, $imageSets);
        }

        return $imageSetCollection;
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
