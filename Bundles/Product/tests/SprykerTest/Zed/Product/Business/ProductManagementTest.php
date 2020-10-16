<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group ProductManagementTest
 * Add your own group annotations below this line
 */
class ProductManagementTest extends FacadeTestAbstract
{
    /**
     * @return void
     */
    public function testAddProductShouldCreateProductAbstractAndConcrete(): void
    {
        $this->productAbstractTransfer->setSku('new-sku');
        $this->productConcreteTransfer->setSku('new-concrete-sku');

        $idProductAbstract = $this->productFacade->addProduct(
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
    public function testSaveProductShouldUpdateProductAbstractAndCreateProductConcrete(): void
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

        $idProductAbstract = $this->productFacade->saveProduct(
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
    public function testSaveProductShouldUpdateProductAbstractAndSaveProductConcrete(): void
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

        $idProductAbstract = $this->productFacade->saveProduct(
            $this->productAbstractTransfer,
            [$this->productConcreteTransfer]
        );

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
        $this->assertSaveProductAbstract($this->productAbstractTransfer);
        $this->assertAddProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testIsProductActiveShouldReturnTrue(): void
    {
        $this->productConcreteTransfer->setIsActive(true);
        $this->setupDefaultProducts();

        $isActive = $this->productFacade->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testIsProductActiveShouldReturnFalse(): void
    {
        $this->productConcreteTransfer->setIsActive(false);
        $this->setupDefaultProducts();

        $isActive = $this->productFacade->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertFalse($isActive);
    }

    /**
     * @return void
     */
    public function testIsProductConcreteActiveShouldReturnTrue(): void
    {
        // Arrange
        $this->productConcreteTransfer->setIsActive(true);
        $this->setupDefaultProducts();

        // Act
        $isActive = $this->productFacade->isProductConcreteActive($this->productConcreteTransfer);

        // Assert
        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testIsProductConcreteActiveShouldReturnFalse(): void
    {
        // Arrange
        $this->productConcreteTransfer->setIsActive(false);
        $this->setupDefaultProducts();

        // Act
        $isActive = $this->productFacade->isProductConcreteActive($this->productConcreteTransfer);

        // Assert
        $this->assertFalse($isActive);
    }

    /**
     * @return void
     */
    public function testCreateProductAbstractSavesStoreRelation(): void
    {
        // Assign
        $expectedIdStores = [1, 3];
        $this->productAbstractTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->setIdStores($expectedIdStores)
        );

        // Act
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        // Asssert
        $actualIdStores = $productAbstractTransfer->getStoreRelation()->getIdStores();
        sort($actualIdStores);

        $this->assertEquals($expectedIdStores, $actualIdStores);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstractUpdatesStoreRelation(): void
    {
        // Assign
        $expectedIdStores = [1, 3];
        $this->productAbstractTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->setIdStores([1])
        );
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productAbstractTransfer->getStoreRelation()->setIdStores($expectedIdStores);

        // Act
        $this->productFacade->saveProductAbstract($this->productAbstractTransfer);
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        // Asssert
        $actualIdStores = $productAbstractTransfer->getStoreRelation()->getIdStores();
        sort($actualIdStores);

        $this->assertEquals($expectedIdStores, $actualIdStores);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractByIdRetrievesStoreRelation(): void
    {
        // Assign
        $expectedIdStores = [1, 3];
        $this->productAbstractTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->setIdStores($expectedIdStores)
        );
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        // Act
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        // Asssert
        $actualIdStores = $productAbstractTransfer->getStoreRelation()->getIdStores();
        sort($actualIdStores);

        $this->assertEquals($expectedIdStores, $actualIdStores);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertAddProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
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
    protected function assertAddProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
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
    protected function assertSaveProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
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

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    protected function getProductConcreteEntityByAbstractId(int $idProductAbstract): ?SpyProduct
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }
}
