<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Spryker\Shared\Kernel\Store;
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
class ProductImageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductImage\ProductImageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected $productAbstractEntity;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $productConcreteEntity;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected $productAbstractSortedEntity;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $productConcreteSortedEntity;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetAbstract;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetConcrete;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetDE;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetEN;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetSortedAbstract;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected $imageSetSortedConcrete;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    protected $imageSetToImage;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    protected $image;

    public const URL_SMALL = 'small';
    public const URL_LARGE = 'large';
    public const SET_NAME = 'Default';
    public const SET_NAME_DE = 'Default DE';
    public const SET_NAME_EN = 'Default EN';
    public const ABSTRACT_SKU = 'abstract-sku';
    public const CONCRETE_SKU = 'concrete-sku';
    public const ABSTRACT_SKU_2 = 'abstract-sku-2';
    public const CONCRETE_SKU_2 = 'concrete-sku-2';
    public const ID_LOCALE_DE = 46;
    public const ID_LOCALE_EN = 66;
    public const LOCALE_DE_DE = 'de_DE';

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->queryContainer = new ProductImageQueryContainer();
        $this->productImageFacade = new ProductImageFacade();

        $this->setupProducts();
        $this->setupImages();
    }

    /**
     * @return void
     */
    protected function setupProducts()
    {
        $this->productAbstractEntity = new SpyProductAbstract();
        $this->productAbstractEntity
            ->setSku(self::ABSTRACT_SKU)
            ->setAttributes('{}')
            ->save();

        $this->productConcreteEntity = new SpyProduct();
        $this->productConcreteEntity
            ->setSku(self::CONCRETE_SKU)
            ->setAttributes('{}')
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->save();

        $this->productAbstractSortedEntity = new SpyProductAbstract();
        $this->productAbstractSortedEntity
            ->setSku(static::ABSTRACT_SKU_2)
            ->setAttributes('{}')
            ->save();

        $this->productConcreteSortedEntity = new SpyProduct();
        $this->productConcreteSortedEntity
            ->setSku(static::CONCRETE_SKU_2)
            ->setAttributes('{}')
            ->setFkProductAbstract($this->productAbstractSortedEntity->getIdProductAbstract())
            ->save();
    }

    /**
     * @return void
     */
    protected function setupImages()
    {
        $this->image = new SpyProductImage();
        $this->image
            ->setExternalUrlLarge(self::URL_LARGE)
            ->setExternalUrlSmall(self::URL_SMALL)
            ->save();

        $this->imageSetAbstract = new SpyProductImageSet();
        $this->imageSetAbstract
            ->setName(self::SET_NAME)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();
        $this->imageSetDE = new SpyProductImageSet();
        $this->imageSetDE
            ->setName(self::SET_NAME_DE)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(self::ID_LOCALE_DE)
            ->save();
        $this->imageSetEN = new SpyProductImageSet();
        $this->imageSetEN
            ->setName(self::SET_NAME_EN)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(self::ID_LOCALE_EN)
            ->save();
        $this->imageSetSortedAbstract = new SpyProductImageSet();
        $this->imageSetSortedAbstract
            ->setName(static::SET_NAME_EN)
            ->setFkProductAbstract($this->productAbstractSortedEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(static::ID_LOCALE_EN)
            ->save();
        $this->imageSetSortedConcrete = new SpyProductImageSet();
        $this->imageSetSortedConcrete
            ->setName(static::SET_NAME_EN)
            ->setFkProductAbstract(null)
            ->setFkProduct($this->productConcreteSortedEntity->getIdProduct())
            ->setFkLocale(static::ID_LOCALE_EN)
            ->save();

        $imageSetToImage = new SpyProductImageSetToProductImage();
        $imageSetToImage
            ->setFkProductImage($this->image->getIdProductImage())
            ->setFkProductImageSet($this->imageSetAbstract->getIdProductImageSet())
            ->setSortOrder(0)
            ->save();
        $imageSetToImage = new SpyProductImageSetToProductImage();
        $imageSetToImage
            ->setFkProductImage($this->image->getIdProductImage())
            ->setFkProductImageSet($this->imageSetDE->getIdProductImageSet())
            ->setSortOrder(0)
            ->save();
        $imageSetToImage = new SpyProductImageSetToProductImage();
        $imageSetToImage
            ->setFkProductImage($this->image->getIdProductImage())
            ->setFkProductImageSet($this->imageSetEN->getIdProductImageSet())
            ->setSortOrder(0)
            ->save();

        $this->imageSetConcrete = new SpyProductImageSet();
        $this->imageSetConcrete
            ->setName(self::SET_NAME)
            ->setFkProductAbstract(null)
            ->setFkProduct($this->productConcreteEntity->getIdProduct())
            ->setFkLocale(null)
            ->save();

        $imageSetToImage = new SpyProductImageSetToProductImage();
        $imageSetToImage
            ->setFkProductImage($this->image->getIdProductImage())
            ->setFkProductImageSet($this->imageSetConcrete->getIdProductImageSet())
            ->setSortOrder(0)
            ->save();
    }

    /**
     * @return void
     */
    public function testPersistProductImageShouldCreateImage()
    {
        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageTransfer = $this->productImageFacade->saveProductImage($productImageTransfer);

        $this->assertCreateImage($productImageTransfer);
    }

    /**
     * @return void
     */
    public function testPersistProductImageSetShouldCreateImageSet()
    {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract());

        $productImageSetTransfer = $this->productImageFacade->saveProductImageSet($productImageSetTransfer);

        $this->assertCreateImageSet($productImageSetTransfer);
    }

    /**
     * @return void
     */
    public function testPersistProductImageSetShouldPersistImageSetAndProductImages()
    {
        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productImageSetTransfer = $this->productImageFacade->saveProductImageSet($productImageSetTransfer);

        $this->assertCreateImageSet($productImageSetTransfer);
        $this->assertAbstractCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractId()
    {
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract()
        );

        $this->assertNotEmpty($productImageSetCollection);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdSortsImagesBySortOrderAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 3);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 1);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 2);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractSortedEntity->getIdProductAbstract()
        )[0]->getProductImages();

        // Assign
        $sortOrder = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getSortOrder() >= $sortOrder);
            $sortOrder = $productImageTransfer->getSortOrder();
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdSortsImagesByIdProductImageSetToProductImageAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractSortedEntity->getIdProductAbstract()
        )[0]->getProductImages();

        // Assign
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue(
                $productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage
            );
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductId()
    {
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductId(
            $this->productConcreteEntity->getIdProduct()
        );

        $this->assertNotEmpty($productImageSetCollection);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdSortsImagesBySortOrderAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 3);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 1);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 2);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductId(
            $this->productConcreteSortedEntity->getIdProduct()
        )[0]->getProductImages();

        // Assign
        $sortOrder = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getSortOrder() >= $sortOrder);
            $sortOrder = $productImageTransfer->getSortOrder();
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdSortsImagesByIdProductImageSetToProductImageAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedConcrete->getIdProductImageSet(), 0);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductId(
            $this->productConcreteSortedEntity->getIdProduct()
        )[0]->getProductImages();

        // Assign
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage);
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocale(): void
    {
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct()
        );

        $this->assertNotEmpty($productImageSetCollection);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocaleReturnsProductImagesSetWithProperLocale(): void
    {
        // Arrange
        $this->tester->createProductImageSet(
            static::SET_NAME_DE,
            null,
            $this->productConcreteEntity->getIdProduct(),
            static::ID_LOCALE_DE
        );

        Store::getInstance()->setCurrentLocale(static::LOCALE_DE_DE);

        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct()
        );

        // Assign
        foreach ($productImageSetCollection as $productImageSetTransfer) {
            static::assertTrue($productImageSetTransfer->getLocale()->getLocaleName() === static::LOCALE_DE_DE);
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocaleReturnsDefaultProductImagesSets(): void
    {
        // Arrange
        $this->tester->createProductImageSet(
            static::SET_NAME_EN,
            null,
            $this->productConcreteEntity->getIdProduct(),
            static::ID_LOCALE_EN
        );

        Store::getInstance()->setCurrentLocale(static::LOCALE_DE_DE);

        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct()
        );

        // Assign
        foreach ($productImageSetCollection as $productImageSetTransfer) {
            static::assertNull($productImageSetTransfer->getLocale());
        }
    }

    /**
     * @return void
     */
    public function testCreateProductAbstractImageSetCollection()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productAbstractTransfer->addImageSet($productImageSetTransfer);

        $this->productImageFacade->createProductAbstractImageSetCollection(
            $productAbstractTransfer
        );

        $this->assertAbstractCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testUpdateProductAbstractImageSetCollection()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setIdProductImage($this->image->getIdProductImage())
            ->setExternalUrlSmall(self::URL_SMALL . 'foo')
            ->setExternalUrlLarge(self::URL_LARGE . 'foo');

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($this->imageSetAbstract->getIdProductImageSet())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productAbstractTransfer->addImageSet($productImageSetTransfer);

        $this->productImageFacade->updateProductAbstractImageSetCollection(
            $productAbstractTransfer
        );

        $this->assertAbstractCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractWithImageSets()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $productAbstractTransfer = $this->productImageFacade->expandProductAbstractWithImageSets(
            $productAbstractTransfer
        );

        $this->assertNotEmpty($productAbstractTransfer->getImageSets());
        foreach ($productAbstractTransfer->getImageSets() as $imageSet) {
            $this->assertNotEmpty($imageSet->getProductImages());

            foreach ($imageSet->getProductImages() as $image) {
                $this->assertInstanceOf(ProductImageTransfer::class, $image);
            }
        }
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteImageSetCollection()
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        $this->productImageFacade->createProductConcreteImageSetCollection(
            $productConcreteTransfer
        );

        $this->assertConcreteCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testUpdateProductConcreteImageSetCollection()
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setIdProductImage($this->image->getIdProductImage())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($this->imageSetConcrete->getIdProductImageSet())
            ->setName(self::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        $this->productImageFacade->updateProductConcreteImageSetCollection(
            $productConcreteTransfer
        );

        $this->assertConcreteCreateImageForImageSet();
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithImageSets()
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productConcreteTransfer = $this->productImageFacade->expandProductConcreteWithImageSets(
            $productConcreteTransfer
        );

        $this->assertNotEmpty($productConcreteTransfer->getImageSets());
        foreach ($productConcreteTransfer->getImageSets() as $imageSet) {
            $this->assertNotEmpty($imageSet->getProductImages());

            foreach ($imageSet->getProductImages() as $image) {
                $this->assertInstanceOf(ProductImageTransfer::class, $image);
            }
        }
    }

    /**
     * @return void
     */
    public function testRemovalProductImageSetFromProductConcrete()
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();
        $productImageSetTransfers = new ArrayObject($this->productImageFacade->getProductImagesSetCollectionByProductId(
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->append($productImageSetTransfer);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->createProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImageSet($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalProductImageSetFromProductAbstract()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();
        $productImageSetTransfers = new ArrayObject($this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->getIdProductAbstract()
        ));

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->append($productImageSetTransfer);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImageSet($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalProductImageFromProductConcrete()
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer();
        $productImageSetTransfers = new ArrayObject();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productImageSetTransfers->append($productImageSetTransfer);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImage($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productConcreteTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductConcreteImageSetCollection($productConcreteTransfer);

        $this->assertConcreteHasNumberOfProductImage($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testRemovalProductImageFromProductAbstract()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();
        $productImageSetTransfers = new ArrayObject();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(self::URL_SMALL)
            ->setExternalUrlLarge(self::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(self::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productImageSetTransfers->append($productImageSetTransfer);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImage($productImageSetTransfers->count());

        $productImageSetTransfers->offsetUnset($productImageSetTransfers->count() - 1);
        $productAbstractTransfer->setImageSets($productImageSetTransfers);
        $this->productImageFacade->updateProductAbstractImageSetCollection($productAbstractTransfer);

        $this->assertAbstractHasNumberOfProductImage($productImageSetTransfers->count());
    }

    /**
     * @return void
     */
    public function testDeleteProductImageSet()
    {
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $imageSet = new SpyProductImageSet();
        $imageSet
            ->setName(self::SET_NAME)
            ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();

        $this->assertProductImageSetExists($imageSet->getIdProductImageSet());

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setIdProductImageSet($imageSet->getIdProductImageSet());

        $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);

        $this->assertProductImageSetNotExists($imageSet->getIdProductImageSet());
    }

    /**
     * @return void
     */
    public function testGetCombinedConcreteImageSets()
    {
        $imageSetTransfers = $this->productImageFacade->getCombinedConcreteImageSets(
            $this->productConcreteEntity->getIdProduct(),
            $this->productConcreteEntity->getFkProductAbstract(),
            static::ID_LOCALE_DE
        );

        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME]);
        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME_DE]);

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $defaultImageSetTransfer */
        $defaultImageSetTransfer = $imageSetTransfers[static::SET_NAME];

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $localizedImageSetTransfer */
        $localizedImageSetTransfer = $imageSetTransfers[static::SET_NAME_DE];

        $defaultProductImages = $defaultImageSetTransfer->getProductImages();
        $localizedProductImages = $localizedImageSetTransfer->getProductImages();

        $this->assertEquals(1, count($defaultProductImages));
        $this->assertEquals(1, count($localizedProductImages));
    }

    /**
     * @return void
     */
    public function testGetCombinedAbstractImageSets()
    {
        $imageSetTransfers = $this->productImageFacade->getCombinedAbstractImageSets(
            $this->productConcreteEntity->getFkProductAbstract(),
            static::ID_LOCALE_DE
        );

        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME]);
        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME_DE]);

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $defaultImageSetTransfer */
        $defaultImageSetTransfer = $imageSetTransfers[static::SET_NAME];

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $localizedImageSetTransfer */
        $localizedImageSetTransfer = $imageSetTransfers[static::SET_NAME_DE];

        $defaultProductImages = $defaultImageSetTransfer->getProductImages();
        $localizedProductImages = $localizedImageSetTransfer->getProductImages();

        $this->assertEquals(1, count($defaultProductImages));
        $this->assertEquals(1, count($localizedProductImages));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return void
     */
    protected function assertCreateImage(ProductImageTransfer $productImageTransfer)
    {
        $productImage = (new SpyProductImageQuery())
            ->filterByIdProductImage($productImageTransfer->getIdProductImage())
            ->findOne();

        $this->assertNotNull($productImage);
        $this->assertEquals($productImageTransfer->getExternalUrlSmall(), $productImage->getExternalUrlSmall());
        $this->assertEquals($productImageTransfer->getExternalUrlLarge(), $productImage->getExternalUrlLarge());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    protected function assertCreateImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $productImage = (new SpyProductImageSetQuery())
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet())
            ->findOne();

        $this->assertNotNull($productImage);
        $this->assertEquals(self::SET_NAME, $productImageSetTransfer->getName());
        $this->assertEquals($this->productAbstractEntity->getIdProductAbstract(), $productImageSetTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    protected function assertAbstractCreateImageForImageSet()
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract()
        );

        $this->assertNotEmpty($imageCollection);
    }

    /**
     * @return void
     */
    protected function assertConcreteCreateImageForImageSet()
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductId(
            $this->productConcreteEntity->getIdProduct()
        );

        $this->assertNotEmpty($imageCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertConcreteHasNumberOfProductImageSet($expectedCount)
    {
        $imageSetCollection = $this->queryContainer->queryImageSetByProductId(
            $this->productConcreteEntity->getIdProduct()
        );

        $this->assertCount($expectedCount, $imageSetCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertAbstractHasNumberOfProductImageSet($expectedCount)
    {
        $imageSetCollection = $this->queryContainer->queryImageSetByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract()
        );

        $this->assertCount($expectedCount, $imageSetCollection);
    }

    /**
     * @param int $idProductImageSet
     *
     * @return void
     */
    protected function assertProductImageSetExists($idProductImageSet)
    {
        $exists = (new SpyProductImageSetQuery())
            ->filterByIdProductImageSet($idProductImageSet)
            ->exists();

        $this->assertTrue($exists);
    }

    /**
     * @param int $idProductImageSet
     *
     * @return void
     */
    protected function assertProductImageSetNotExists($idProductImageSet)
    {
        $exists = (new SpyProductImageSetQuery())
            ->filterByIdProductImageSet($idProductImageSet)
            ->exists();

        $this->assertFalse($exists);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertAbstractHasNumberOfProductImage($expectedCount)
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract()
        );

        $this->assertCount($expectedCount, $imageCollection);
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertConcreteHasNumberOfProductImage($expectedCount)
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductId(
            $this->productConcreteEntity->getIdProduct()
        );

        $this->assertCount($expectedCount, $imageCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer()
    {
        $productData = $this->productAbstractEntity->toArray();
        unset($productData[ProductAbstractTransfer::ATTRIBUTES]);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->fromArray($productData, true);

        return $productAbstractTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer()
    {
        $productData = $this->productConcreteEntity->toArray();
        unset($productData[ProductAbstractTransfer::ATTRIBUTES]);

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete($this->productConcreteEntity->getIdProduct())
            ->fromArray($productData, true);

        return $productConcreteTransfer;
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetById()
    {
        $productImageSetTransfer = $this->productImageFacade->findProductImageSetById(
            $this->imageSetAbstract->getIdProductImageSet()
        );

        $this->assertNotEmpty($productImageSetTransfer);
        $this->assertCount(1, $productImageSetTransfer->getProductImages());
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetByIdWithoutImages()
    {
        $imageSetEntity = new SpyProductImageSet();
        $imageSetEntity
            ->setName(self::SET_NAME)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();

        $productImageSetTransfer = $this->productImageFacade->findProductImageSetById(
            $imageSetEntity->getIdProductImageSet()
        );

        $this->assertNotEmpty($productImageSetTransfer);
        $this->assertCount(0, $productImageSetTransfer->getProductImages());
    }

    /**
     * @return void
     */
    public function testFindProductImageSetByIdSortsImagesBySortOrderAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 3);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 1);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 2);

        // Act
        $productImageCollection = $this->productImageFacade->findProductImageSetById(
            $this->imageSetSortedAbstract->getIdProductImageSet()
        )->getProductImages();

        // Assign
        $sortOrder = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getSortOrder() >= $sortOrder);
            $sortOrder = $productImageTransfer->getSortOrder();
        }
    }

    /**
     * @return void
     */
    public function testFindProductImageSetByIdSortsImagesByidProductImageSetToProductImageAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);

        // Act
        $productImageCollection = $this->productImageFacade->findProductImageSetById(
            $this->imageSetSortedAbstract->getIdProductImageSet()
        )->getProductImages();

        // Assign
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage);
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }

    /**
     * @return void
     */
    public function testGetDefaultProductImagesByProductIdsReturnsImages(): void
    {
        //Arrange
        $productIds = [$this->productConcreteEntity->getIdProduct()];

        //Act
        $productImagesCollection = $this->productImageFacade->getProductImagesByProductIdsAndProductImageSetName($productIds, static::SET_NAME);

        //Assert
        $this->assertCount(count($productIds), $productImagesCollection);
        $this->assertEquals($productIds, array_keys($productImagesCollection));
        $this->assertNotEmpty($productImagesCollection[$this->productConcreteEntity->getIdProduct()]);
    }
}
