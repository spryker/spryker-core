<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * Auto-generated group annotations
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
    public function testAddProductShouldCreateProductAbstractAndConcrete()
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
    public function testIsProductActiveShouldReturnTrue()
    {
        $this->productConcreteTransfer->setIsActive(true);
        $this->setupDefaultProducts();

        $isActive = $this->productFacade->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testIsProductActiveShouldReturnFalse()
    {
        $this->productConcreteTransfer->setIsActive(false);
        $this->setupDefaultProducts();

        $isActive = $this->productFacade->isProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertFalse($isActive);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractStoreRelationRetrievesRelatedStores()
    {
        // Assign
        $idProductAbstract = 1;
        $relatedStores = [1, 3];
        $productAbstractRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract);
        $expectedResult = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($relatedStores)
            ->setStores(new ArrayObject());

        $this->productFacade->saveProductAbstractStoreRelation($expectedResult);

        // Act
        $actualResult = $this
            ->productFacade
            ->getProductAbstractStoreRelation($productAbstractRelationRequest);

        // Assert
        $actualResult->setStores(new ArrayObject());

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider relationUpdate
     *
     * @reutrn void
     *
     * @param int[] $originalRelation
     * @param int[] $modifiedRelation
     *
     * @return void
     */
    public function testSaveProductAbstractStoreRelation(array $originalRelation, array $modifiedRelation)
    {
        // Assign
        $idProductAbstract = 1;
        $productAbstractRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract);
        $originalRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($originalRelation);
        $modifiedRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($modifiedRelation);

        $this->productFacade->saveProductAbstractStoreRelation($originalRelationTransfer);

        // Act
        $beforeSaveIdStores = $this
            ->productFacade
            ->getProductAbstractStoreRelation($productAbstractRelationRequest)
            ->getIdStores();
        $this->productFacade->saveProductAbstractStoreRelation($modifiedRelationTransfer);
        $afterSaveIdStores = $this
            ->productFacade
            ->getProductAbstractStoreRelation($productAbstractRelationRequest)
            ->getIdStores();

        // Assert
        sort($beforeSaveIdStores);
        sort($afterSaveIdStores);
        $this->assertEquals($originalRelation, $beforeSaveIdStores);
        $this->assertEquals($modifiedRelation, $afterSaveIdStores);
    }

    /**
     * @return array
     */
    public function relationUpdate()
    {
        return [
            [
                [1, 2, 3], [2],
            ],
            [
                [1], [1, 2],
            ],
            [
                [2], [1, 3],
            ],
        ];
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
