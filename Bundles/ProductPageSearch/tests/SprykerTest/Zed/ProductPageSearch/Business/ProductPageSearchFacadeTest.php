<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group Facade
 * @group ProductPageSearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductPageSearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    protected $productPageSearchFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tester->setUp();

        $this->productPageSearchFacade = new ProductPageSearchFacade();
    }

    /**
     * @return void
     */
    public function testUnpublishProductConcretePageSearches(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);
        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        $productAbstractStoreMap = [
            $productAbstractTransfer->getIdProductAbstract() => [$storeNames[0]],
        ];
        unset($storeNames[0]);
        $this->productPageSearchFacade->unpublishProductConcretePageSearches($productAbstractStoreMap);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }

    /**
     * @return void
     */
    public function testPublishProductConcretePageSearchesByProductAbstractIds(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsDataWithProductImages(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertContains(
            $productImageSetTransfer->getProductImages()->offsetGet(0)->toArray(false, true),
            $expandedProductConcretePageSearchTransfer->getImages()
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutImages(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertEmpty($expandedProductConcretePageSearchTransfer->getImages());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertEmpty($expandedProductConcretePageSearchTransfer->getImages());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutRequiredLocaleField(): void
    {
        // Arrange
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($this->tester->haveProduct()->getIdProductConcrete())
            ->setLocale(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutRequiredIdProductField(): void
    {
        // Arrange
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct(null)
            ->setLocale($this->tester->haveLocale()->getLocaleName());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);
    }
}
