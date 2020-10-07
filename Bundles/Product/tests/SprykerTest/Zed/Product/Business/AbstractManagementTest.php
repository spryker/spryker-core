<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group AbstractManagementTest
 * Add your own group annotations below this line
 */
class AbstractManagementTest extends FacadeTestAbstract
{
    /**
     * @return void
     */
    public function testCreateProductAbstractShouldCreateProductAbstract(): void
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assertCreateProductAbstract($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstractShouldUpdateProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        $idProductAbstract = $this->productFacade->saveProductAbstract($this->productAbstractTransfer);

        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assertSaveProductAbstract($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnFalse(): void
    {
        $this->assertFalse(
            $this->productFacade->hasProductAbstract('sku that does not exist')
        );
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnTrue(): void
    {
        $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->assertTrue(
            $this->productFacade->hasProductAbstract(self::ABSTRACT_SKU)
        );
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySku(): void
    {
        $expectedId = $this->createNewProductAbstractAndAssertNoTouchExists();
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku(self::ABSTRACT_SKU);

        $this->assertEquals(
            $expectedId,
            $idProductAbstract
        );
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySkuShouldReturnNull(): void
    {
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku('INVALIDSKU');

        $this->assertNull($idProductAbstract);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractById(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $productAbstract = $this->productFacade->findProductAbstractById($idProductAbstract);

        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstract);
        $this->assertSame(static::ABSTRACT_SKU, $productAbstract->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractByIdShouldReturnNull(): void
    {
        $productAbstract = $this->productFacade->findProductAbstractById(1010001);

        $this->assertNull($productAbstract);
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcrete(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete(self::CONCRETE_SKU);

        $this->assertSame(static::ABSTRACT_SKU, $abstractSku);
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcreteShouldThrowException(): void
    {
        $this->expectException(MissingProductException::class);
        $this->expectExceptionMessage('Tried to retrieve a product concrete with sku INVALIDSKU, but it does not exist.');

        $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->getAbstractSkuFromProductConcrete('INVALIDSKU');
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductAbstractName(): void
    {
        $nameEN = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['en_US']
        );

        $nameDE = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['de_DE']
        );

        $this->assertSame(static::PRODUCT_ABSTRACT_NAME['en_US'], $nameEN);
        $this->assertSame(static::PRODUCT_ABSTRACT_NAME['de_DE'], $nameDE);
    }

    /**
     * @return void
     */
    public function testTouchProductAbstractShouldAlsoTouchItsVariants(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $this->productFacade->touchProductAbstract($idProductAbstract);

        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $idProductConcrete);
    }

    /**
     * @return void
     */
    public function testTouchProductActiveShouldTouchActiveLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductActive($idProductAbstract);

        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testTouchProductInactiveShouldTouchInactiveLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductActive($idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productFacade->touchProductInactive($idProductAbstract);
        $this->tester->assertTouchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchInactive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testTouchProductDeletedShouldTouchDeletedLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductDeleted($idProductAbstract);

        $this->tester->assertTouchDeleted(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchDeleted(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    /**
     * @return int
     */
    protected function createNewProductAbstractAndAssertNoTouchExists(): int
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->tester->assertNoTouchEntry(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertNoTouchEntry(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertCreateProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $createdProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($createdProductEntity);
        $this->assertSame($productAbstractTransfer->getSku(), $createdProductEntity->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertSaveProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $updatedProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($updatedProductEntity);
        $this->assertSame($this->productAbstractTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertSame($expectedProductName, $localizedAttribute->getName());
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    protected function getProductAbstractEntityById(int $idProductAbstract): ?SpyProductAbstract
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }
}
