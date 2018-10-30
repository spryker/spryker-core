<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\CategoryImage\Business\CategoryImageFacade;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepository;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group Facade
 * @group ProductImageFacadeTest
 * Add your own group annotations below this line
 */
class CategoryImageFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface
     */
    protected $categoryImageFacade;

    /**
     * @var \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected $categoryEntity;

    /**
     * @var \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    protected $imageSetCategory;

    /**
     * @var \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    protected $imageSetDE;

    /**
     * @var \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    protected $imageSetEN;

    /**
     * @var \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage
     */
    protected $imageSetToImage;

    /**
     * @var \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage
     */
    protected $image;

    public const URL_SMALL = 'small';
    public const URL_LARGE = 'large';
    public const SET_NAME = 'Default';
    public const SET_NAME_DE = 'Default DE';
    public const SET_NAME_EN = 'Default EN';
    public const ABSTRACT_SKU = 'abstract-sku';
    public const CONCRETE_SKU = 'concrete-sku';
    public const ID_LOCALE_DE = 46;
    public const ID_LOCALE_EN = 66;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->repository = new CategoryImageRepository();
        $this->categoryImageFacade = new CategoryImageFacade();

        $this->setupCategories();
        $this->setupImages();
    }

    /**
     * @return void
     */
    protected function setupCategories()
    {
        $this->categoryEntity = new SpyCategory();
        $this->categoryEntity->setAttributes(new Collection())->save();
    }

    /**
     * @return void
     */
    protected function setupImages()
    {
        $this->image = new SpyCategoryImage();
        $this->image
            ->setExternalUrlLarge(static::URL_LARGE)
            ->setExternalUrlSmall(static::URL_SMALL)
            ->save();

        $this->imageSetCategory = new SpyCategoryImageSet();
        $this->imageSetCategory
            ->setName(static::SET_NAME)
            ->setFkCategory($this->categoryEntity->getIdCategory())
            ->setFkLocale(null)
            ->save();

        $this->imageSetDE = new SpyCategoryImageSet();
        $this->imageSetDE
            ->setName(static::SET_NAME_DE)
            ->setFkCategory($this->categoryEntity->getIdCategory())
            ->setFkLocale(static::ID_LOCALE_DE)
            ->save();
        $this->imageSetEN = new SpyCategoryImageSet();
        $this->imageSetEN
            ->setName(static::SET_NAME_EN)
            ->setFkCategory($this->categoryEntity->getIdCategory())
            ->setFkLocale(static::ID_LOCALE_EN)
            ->save();

        $imageSetToImage = new SpyCategoryImageSetToCategoryImage();
        $imageSetToImage
            ->setFkCategoryImage($this->image->getIdCategoryImage())
            ->setFkCategoryImageSet($this->imageSetCategory->getIdCategoryImageSet())
            ->setSortOrder(0)
            ->save();
        $imageSetToImage = new SpyCategoryImageSetToCategoryImage();
        $imageSetToImage
            ->setFkCategoryImage($this->image->getIdCategoryImage())
            ->setFkCategoryImageSet($this->imageSetDE->getIdCategoryImageSet())
            ->setSortOrder(0)
            ->save();
        $imageSetToImage = new SpyCategoryImageSetToCategoryImage();
        $imageSetToImage
            ->setFkCategoryImage($this->image->getIdCategoryImage())
            ->setFkCategoryImageSet($this->imageSetEN->getIdCategoryImageSet())
            ->setSortOrder(0)
            ->save();
    }

    /**
     * @return void
     */
    public function testPersistCategoryImageShouldCreateImage()
    {
        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $categoryImageTransfer = $this->categoryImageFacade->saveCategoryImage($categoryImageTransfer);

        $this->assertCreateImage($categoryImageTransfer);
    }

    /**
     * @return void
     */
    public function testPersistCategoryImageSetShouldCreateImageSet()
    {
        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory());

        $categoryImageSetTransfer = $this->categoryImageFacade->saveCategoryImageSet($categoryImageSetTransfer);

        $this->assertCreateImageSet($categoryImageSetTransfer);
    }

    /**
     * @return void
     */
    public function testPersistCategoryImageSetShouldPersistImageSetAndCategoryImages()
    {
        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory())
            ->addCategoryImage($categoryImageTransfer);

        $categoryImageSetTransfer = $this->categoryImageFacade->saveCategoryImageSet($categoryImageSetTransfer);

        $this->assertCreateImageSet($categoryImageSetTransfer);
        $this->assertCategoryCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testGetCategoryImagesSetCollectionByCategoryId()
    {
        $categoryImageSetCollection = $this->categoryImageFacade->getCategoryImagesSetCollectionByCategoryId(
            $this->categoryEntity->getIdCategory()
        );

        $this->assertNotEmpty($categoryImageSetCollection);
    }

    /**
     * @return void
     */
    public function testCreateCategoryImageSetCollection()
    {
        $categoryTransfer = $this->createCategoryTransfer();

        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory())
            ->addCategoryImage($categoryImageTransfer);

        $categoryTransfer->addImageSet($categoryImageSetTransfer);

        $this->categoryImageFacade->createCategoryImageSetCollection(
            $categoryTransfer
        );

        $this->assertCategoryCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testUpdateCategoryImageSetCollection()
    {
        $categoryTransfer = $this->createCategoryTransfer();

        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setIdCategoryImage($this->image->getIdCategoryImage())
            ->setExternalUrlSmall(static::URL_SMALL . 'foo')
            ->setExternalUrlLarge(static::URL_LARGE . 'foo');

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setIdCategoryImageSet($this->imageSetCategory->getIdCategoryImageSet())
            ->setName(static::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory())
            ->addCategoryImage($categoryImageTransfer);

        $categoryTransfer->addImageSet($categoryImageSetTransfer);

        $this->categoryImageFacade->updateCategoryImageSetCollection(
            $categoryTransfer
        );

        $this->assertCategoryCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testExpandCategoryWithImageSets()
    {
        $categoryTransfer = $this->createCategoryTransfer();

        $categoryTransfer = $this->categoryImageFacade->expandCategoryWithImageSets(
            $categoryTransfer
        );

        $this->assertNotEmpty($categoryTransfer->getFormImageSets());
        foreach ($categoryTransfer->getFormImageSets() as $imageSet) {
            $this->assertNotEmpty($imageSet->getCategoryImages());

            foreach ($imageSet->getCategoryImages() as $image) {
                $this->assertInstanceOf(CategoryImageTransfer::class, $image);
            }
        }
    }

    /**
     * @return void
     */
    public function testRemovalCategoryImageSetFromCategory()
    {
        $categoryTransfer = $this->createCategoryTransfer();
        $categoryImageSetTransfers = new ArrayObject($this->categoryImageFacade->getCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->getIdCategory()
        ));

        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory())
            ->addCategoryImage($categoryImageTransfer);

        $this->assertCategoryHasNumberOfCategoryImageSet($categoryImageSetTransfers->count());

        $categoryImageSetTransfers->append($categoryImageSetTransfer);
        $categoryTransfer->setFormImageSets($categoryImageSetTransfers);
        $this->categoryImageFacade->updateCategoryImageSetCollection($categoryTransfer);

        $this->assertCategoryHasNumberOfCategoryImageSet($categoryImageSetTransfers->count());

        $categoryImageSetTransfers->offsetUnset($categoryImageSetTransfers->count() - 1);
        $categoryTransfer->setFormImageSets($categoryImageSetTransfers);
        $this->categoryImageFacade->updateCategoryImageSetCollection($categoryTransfer);

        $this->assertCategoryHasNumberOfCategoryImageSet($categoryImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalCategoryImageFromCategory()
    {
        $categoryTransfer = $this->createCategoryTransfer();
        $categoryImageSetTransfers = new ArrayObject();

        $categoryImageTransfer = (new CategoryImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdCategory($this->categoryEntity->getIdCategory())
            ->addCategoryImage($categoryImageTransfer);

        $categoryImageSetTransfers->append($categoryImageSetTransfer);
        $categoryTransfer->setFormImageSets($categoryImageSetTransfers);
        $this->categoryImageFacade->updateCategoryImageSetCollection($categoryTransfer);

        $this->assertCategoryHasNumberOfCategoryImage($categoryImageSetTransfers->count());

        $categoryImageSetTransfers->offsetUnset($categoryImageSetTransfers->count() - 1);
        $categoryTransfer->setFormImageSets($categoryImageSetTransfers);
        $this->categoryImageFacade->updateCategoryImageSetCollection($categoryTransfer);

        $this->assertCategoryHasNumberOfCategoryImage($categoryImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testDeleteCategoryImageSet()
    {
        $categoryTransfer = $this->createCategoryTransfer();

        $imageSet = new SpyCategoryImageSet();
        $imageSet
            ->setName(static::SET_NAME)
            ->setFkCategory($categoryTransfer->getIdCategory())
            ->setFkLocale(null)
            ->save();

        $this->assertCategoryImageSetExists($imageSet->getIdCategoryImageSet());

        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->setIdCategoryImageSet($imageSet->getIdCategoryImageSet());

        $this->categoryImageFacade->deleteCategoryImageSet($categoryImageSetTransfer);

        $this->assertCategoryImageSetNotExists($imageSet->getIdCategoryImageSet());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return void
     */
    protected function assertCreateImage(CategoryImageTransfer $categoryImageTransfer)
    {
        $categoryImage = (new SpyCategoryImageQuery())
            ->filterByIdCategoryImage($categoryImageTransfer->getIdCategoryImage())
            ->findOne();

        $this->assertNotNull($categoryImage);
        $this->assertEquals($categoryImageTransfer->getExternalUrlSmall(), $categoryImage->getExternalUrlSmall());
        $this->assertEquals($categoryImageTransfer->getExternalUrlLarge(), $categoryImage->getExternalUrlLarge());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function assertCreateImageSet(CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $categoryImage = (new SpyCategoryImageSetQuery())
            ->filterByIdCategoryImageSet($categoryImageSetTransfer->getIdCategoryImageSet())
            ->findOne();

        $this->assertNotNull($categoryImage);
        $this->assertEquals(static::SET_NAME, $categoryImageSetTransfer->getName());
        $this->assertEquals($this->categoryEntity->getIdCategory(), $categoryImageSetTransfer->getIdCategory());
    }

    /**
     * @return void
     */
    protected function assertCategoryCreateImageForImageSet()
    {
        $imageCollection = $this->repository->findCategoryImageSetsByCategoryId(
            $this->categoryEntity->getIdCategory()
        );

        $this->assertNotEmpty($imageCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertCategoryHasNumberOfCategoryImageSet($expectedCount)
    {
        $imageSetCollection = $this->repository->findCategoryImageSetsByCategoryId(
            $this->categoryEntity->getIdCategory()
        );

        $this->assertCount($expectedCount, $imageSetCollection);
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    protected function assertCategoryImageSetExists(int $idCategoryImageSet)
    {
        $exists = (new SpyCategoryImageSetQuery())
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->exists();

        $this->assertTrue($exists);
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    protected function assertCategoryImageSetNotExists(int $idCategoryImageSet)
    {
        $exists = (new SpyCategoryImageSetQuery())
            ->filterByIdCategoryImageSet($idCategoryImageSet)
            ->exists();

        $this->assertFalse($exists);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertCategoryHasNumberOfCategoryImage(int $expectedCount)
    {
        $imageCollection = $this->repository->findCategoryImageSetsByCategoryId(
            $this->categoryEntity->getIdCategory()
        );

        $this->assertCount($expectedCount, $imageCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategoryTransfer()
    {
        $categoryData = $this->categoryEntity->toArray();
        unset($categoryData[CategoryTransfer::LOCALIZED_ATTRIBUTES]);

        return (new CategoryTransfer())
            ->fromArray($categoryData, true);
    }
}
