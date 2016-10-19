<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductFacadeTest
 */
class ProductFacadeTest extends ProductTestAbstract
{

    /**
     * @return void
     */
    protected function setupProductConcrete()
    {
        $this->productConcreteTransfer = new ProductConcreteTransfer();
        $this->productConcreteTransfer
            ->setSku('foo-concrete');

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_CONCRETE_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_CONCRETE_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    protected function setupDefaultProducts()
    {
        $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @return void
     */
    public function testAddProductShouldAddProduct()
    {
        $idProductAbstract = $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testSaveProductShouldSaveProduct()
    {
        $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $idProductAbstract = $this->productFacade->saveProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testCreateProductAbstract()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstract()
    {
        $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $idProductAbstract = $this->productFacade->saveProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnTrue()
    {
        $this->setupDefaultProducts();

        $exists = $this->productFacade->hasProductAbstract($this->productAbstractTransfer->getSku());

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnFalse()
    {
        $exists = $this->productFacade->hasProductAbstract('INVALIDSKU');

        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySku()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $id = $this->productFacade->getProductAbstractIdBySku($this->productAbstractTransfer->getSku());

        $this->assertEquals($idProductAbstract, $id);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractById()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $productAbstract = $this->productFacade->getProductAbstractById($idProductAbstract);

        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstract);
        $this->assertEquals($idProductAbstract, $productAbstract->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcrete()
    {
        $this->setupDefaultProducts();

        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getSku(), $abstractSku);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdByConcreteSku()
    {
        $this->setupDefaultProducts();

        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteSku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testCreateProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $this->assertTrue($idProductConcrete > 0);
        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $idProductConcrete);
    }

    /**
     * @return void
     */
    public function testSaveProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $idProductConcrete = $this->productFacade->saveProductConcrete($this->productConcreteTransfer);

        $this->assertTrue($idProductConcrete > 0);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnTrue()
    {
        $this->setupDefaultProducts();

        $exists = $this->productFacade->hasProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnFalse()
    {
        $exists = $this->productFacade->hasProductConcrete('INVALIDSKU');

        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdBySku()
    {
        $this->setupDefaultProducts();

        $id = $this->productFacade->getProductConcreteIdBySku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $id);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteById()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $productConcrete = $this->productFacade->getProductConcreteById($idProductConcrete);

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
        $this->assertEquals($idProductConcrete, $productConcrete->getIdProductConcrete());
    }

    /**
     * @return void
     */
    public function testGetProductConcrete()
    {
        $this->setupDefaultProducts();

        $productConcrete = $this->productFacade->getProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteShouldThrowException()
    {
        $this->expectException(MissingProductException::class);
        $this->expectExceptionMessage('Tried to retrieve a product concrete with sku INVALIDSKU, but it does not exist.');

        $this->productFacade->getProductConcrete('INVALIDSKU');
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsByAbstractProductId()
    {
        $this->setupDefaultProducts();

        $productConcreteCollection = $this->productFacade->getConcreteProductsByAbstractProductId($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertNotEmpty((array)$productConcreteCollection);

        foreach ($productConcreteCollection as $productConcrete) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
            $this->assertEquals(
                $this->productAbstractTransfer->getIdProductAbstract(),
                $this->productConcreteTransfer->getFkProductAbstract()
            );
        }
    }

    /**
     * @return void
     */
    public function testTouchProductActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductActive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductInActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductInactive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductDeleted()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductDeleted($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteActive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteInactive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteInactive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteDelete()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteDelete($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testCreateProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testUpdateProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testGetProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrl()
    {
        $this->setupDefaultProducts();

        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testTouchProductAbstractUrlActive()
    {
        $this->setupDefaultProducts();
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlActive($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testTouchProductAbstractUrlDeleted()
    {
        $this->setupDefaultProducts();
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlDeleted($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductAbstractName()
    {
        $this->setupDefaultProducts();

        $productNameEN = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['en_US']
        );

        $productNameDE = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['de_DE']
        );

        $this->assertEquals(self::PRODUCT_ABSTRACT_NAME['en_US'], $productNameEN);
        $this->assertEquals(self::PRODUCT_ABSTRACT_NAME['de_DE'], $productNameDE);
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductConcreteName()
    {
        $this->setupDefaultProducts();

        $productNameEN = $this->productFacade->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['en_US']
        );

        $productNameDE = $this->productFacade->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['de_DE']
        );

        $this->assertEquals(self::PRODUCT_CONCRETE_NAME['en_US'], $productNameEN);
        $this->assertEquals(self::PRODUCT_CONCRETE_NAME['de_DE'], $productNameDE);
    }

    /**
     * @return void
     */
    public function testActivateProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $this->productFacade->activateProductConcrete($idProductConcrete);

        $active = $this->productQueryContainer->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->filterByIsActive(true)
            ->count() > 0;

        $this->assertTrue($active);
    }

    /**
     * @return void
     */
    public function testDeactivateProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $this->productFacade->deactivateProductConcrete($idProductConcrete);

        $active = $this->productQueryContainer->queryProduct()
                ->filterByIdProduct($idProductConcrete)
                ->filterByIsActive(false)
                ->count() > 0;

        $this->assertTrue($active);
    }

}
