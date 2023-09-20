<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group Facade
 * @group AbstractProductImageFacadeTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductImageFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const URL_SMALL = 'small';

    /**
     * @var string
     */
    protected const URL_LARGE = 'large';

    /**
     * @var string
     */
    protected const SET_NAME = 'Default';

    /**
     * @var string
     */
    protected const SET_NAME_DE = 'Default DE';

    /**
     * @var string
     */
    protected const SET_NAME_EN = 'Default EN';

    /**
     * @var string
     */
    protected const ABSTRACT_SKU = 'abstract-sku';

    /**
     * @var string
     */
    protected const CONCRETE_SKU = 'concrete-sku';

    /**
     * @var string
     */
    protected const ABSTRACT_SKU_2 = 'abstract-sku-2';

    /**
     * @var string
     */
    protected const CONCRETE_SKU_2 = 'concrete-sku-2';

    /**
     * @var int
     */
    protected const ID_LOCALE_DE = 46;

    /**
     * @var int
     */
    protected const ID_LOCALE_EN = 66;

    /**
     * @var string
     */
    protected const LOCALE_DE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_EN_EN = 'en_EN';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryContainer = new ProductImageQueryContainer();
        $this->productImageFacade = new ProductImageFacade();

        $this->setupProducts();
        $this->setupImages();

        $this->tester->getContainer()->set(static::SERVICE_LOCALE, static::LOCALE_DE_DE);
    }

    /**
     * @return void
     */
    protected function setupProducts(): void
    {
        $this->productAbstractEntity = new SpyProductAbstract();
        $this->productAbstractEntity
            ->setSku(static::ABSTRACT_SKU)
            ->setAttributes('{}')
            ->save();

        $this->productConcreteEntity = new SpyProduct();
        $this->productConcreteEntity
            ->setSku(static::CONCRETE_SKU)
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
    protected function setupImages(): void
    {
        $this->image = new SpyProductImage();
        $this->image
            ->setExternalUrlLarge(static::URL_LARGE)
            ->setExternalUrlSmall(static::URL_SMALL)
            ->save();

        $this->imageSetAbstract = new SpyProductImageSet();
        $this->imageSetAbstract
            ->setName(static::SET_NAME)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();
        $this->imageSetDE = new SpyProductImageSet();
        $this->imageSetDE
            ->setName(static::SET_NAME_DE)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(static::ID_LOCALE_DE)
            ->save();
        $this->imageSetEN = new SpyProductImageSet();
        $this->imageSetEN
            ->setName(static::SET_NAME_EN)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(static::ID_LOCALE_EN)
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
            ->setName(static::SET_NAME)
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
    protected function assertAbstractCreateImageForImageSet(): void
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        $this->assertNotEmpty($imageCollection);
    }

    /**
     * @return void
     */
    protected function assertConcreteCreateImageForImageSet(): void
    {
        $imageCollection = $this->queryContainer->queryImageCollectionByProductId(
            $this->productConcreteEntity->getIdProduct(),
        );

        $this->assertNotEmpty($imageCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(): ProductAbstractTransfer
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
    protected function createProductConcreteTransfer(): ProductConcreteTransfer
    {
        $productData = $this->productConcreteEntity->toArray();
        unset($productData[ProductAbstractTransfer::ATTRIBUTES]);

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete($this->productConcreteEntity->getIdProduct())
            ->fromArray($productData, true);

        return $productConcreteTransfer;
    }
}
