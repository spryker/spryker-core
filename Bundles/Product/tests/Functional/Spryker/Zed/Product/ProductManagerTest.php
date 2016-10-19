<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeProcessor;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductManagerTest
 */
class ProductManagerTest extends ProductTestAbstract
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
        $this->productManager->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @return void
     */
    public function testAddProductShouldCreateProductAbstractAndConcrete()
    {
        $this->productAbstractTransfer->setSku('new-sku');
        $this->productConcreteTransfer->setSku('new-concrete-sku');

        $idProductAbstract = $this->productManager->addProduct(
            $this->productAbstractTransfer,
            [$this->productConcreteTransfer]
        );

        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertAddProductAbstract($this->productAbstractTransfer);
        $this->assertAddProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductShouldUpdateProductAbstractAndCreateProductConcrete()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        foreach ($this->productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_CONCRETE_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        $idProductAbstract = $this->productManager->saveProduct(
            $this->productAbstractTransfer,
            [$this->productConcreteTransfer]
        );

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
        $this->assertSaveProductAbstract($this->productAbstractTransfer);
        $this->assertSaveProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductShouldUpdateProductAbstractAndSaveProductConcrete()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $idProductAbstract = $this->productManager->saveProduct(
            $this->productAbstractTransfer,
            [$this->productConcreteTransfer]
        );

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
        $this->assertSaveProductAbstract($this->productAbstractTransfer);
        //$this->assertSaveProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductAttributeProcessor()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $attributeProcessor = $this->productManager->getProductAttributeProcessor($idProductAbstract);

        $this->assertInstanceOf(AttributeProcessor::class, $attributeProcessor);
    }

    /**
     * @return void
     */
    public function testIsProductActiveShouldReturnTrue()
    {
        $this->productConcreteTransfer->setIsActive(true);
        $this->setupDefaultProducts();

        $isActive = $this->productManager->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testIsProductActiveShouldReturnFalse()
    {
        $this->productConcreteTransfer->setIsActive(false);
        $this->setupDefaultProducts();

        $isActive = $this->productManager->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertFalse($isActive);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertAddProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $createdProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($createdProductEntity);
        $this->assertEquals($productAbstractTransfer->getSku(), $createdProductEntity->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertSaveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $updatedProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($updatedProductEntity);
        $this->assertEquals($this->productAbstractTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertEquals($expectedProductName, $localizedAttribute->getName());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertAddProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $createdProductEntity = $this->getProductConcreteEntityByAbstractId(
            $productConcreteTransfer->getFkProductAbstract()
        );

        $this->assertNotNull($createdProductEntity);
        $this->assertEquals($productConcreteTransfer->getSku(), $createdProductEntity->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertSaveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $updatedProductEntity = $this->getProductConcreteEntityByAbstractId(
            $productConcreteTransfer->getFkProductAbstract()
        );

        $this->assertNotNull($updatedProductEntity);
        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $updatedProductEntity->getPrimaryKey());
        $this->assertEquals($this->productConcreteTransfer->getSku(), $updatedProductEntity->getSku());

        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId(
            $productConcreteTransfer->getFkProductAbstract()
        );

        $productConcreteTransferExpected = $productConcreteCollection[0];
        foreach ($productConcreteTransferExpected->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = self::UPDATED_PRODUCT_CONCRETE_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertEquals($expectedProductName, $localizedAttribute->getName());
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntityById($idProductAbstract)
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductConcreteEntityByAbstractId($idProductAbstract)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }

}
