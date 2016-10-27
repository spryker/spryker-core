<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductImage\Business\Model;

use Codeception\TestCase\Test;
use Functional\Spryker\Zed\ProductOption\Mock\LocaleFacade;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group ProductImageFacadeTest
 */
class ProductImageFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected $productAbstractEntity;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $productConcreteEntity;

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
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    protected $imageSetToImage;

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    protected $image;

    const URL_SMALL = 'small';
    const URL_LARGE = 'large';
    const SET_NAME = 'Default';
    const SET_NAME_DE = 'Default DE';
    const SET_NAME_EN = 'Default EN';
    const ABSTRACT_SKU = 'abstract-sku';
    const CONCRETE_SKU = 'concrete-sku';
    const ID_LOCALE_DE = 46;
    const ID_LOCALE_EN = 66;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->queryContainer = new ProductImageQueryContainer();
        $this->productImageFacade = new ProductImageFacade();
        $this->localeFacade = new LocaleFacade();

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

        $productImageTransfer = $this->productImageFacade->persistProductImage($productImageTransfer);

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

        $productImageSetTransfer = $this->productImageFacade->persistProductImageSet($productImageSetTransfer);

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

        $productImageSetTransfer = $this->productImageFacade->persistProductImageSet($productImageSetTransfer);

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

}
