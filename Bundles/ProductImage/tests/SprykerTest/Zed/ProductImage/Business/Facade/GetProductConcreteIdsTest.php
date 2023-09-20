<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetProductConcreteIdsTest
 * Add your own group annotations below this line
 */
class GetProductConcreteIdsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetProductConcreteIdsFilteredByProductImageIds(): void
    {
        // Arrange
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteEntity->getIdProduct(),
        ]);

        $productImageFilterTransfer = (new ProductImageFilterTransfer())
            ->addProductImageId($productImageSetTransfer->getProductImages()->offsetGet(0)->getIdProductImage());

        // Act
        $productConcreteIds = array_map(
            'intval',
            $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer),
        );

        // Assert
        $this->assertCount(1, $productConcreteIds);
        $this->assertContains($productImageSetTransfer->getIdProduct(), $productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsFilteredByProductImageIdsWithEmptyResult(): void
    {
        // Arrange
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractEntity->getIdProductAbstract(),
        ]);

        $productImageFilterTransfer = (new ProductImageFilterTransfer())
            ->addProductImageId($productImageSetTransfer->getProductImages()->offsetGet(0)->getIdProductImage());

        // Act
        $productConcreteIds = $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer);

        // Assert
        $this->assertEmpty($productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsFilteredByProductImageSetIds(): void
    {
        // Arrange
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteEntity->getIdProduct(),
        ]);

        $productImageFilterTransfer = (new ProductImageFilterTransfer())
            ->addProductImageSetId($productImageSetTransfer->getIdProductImageSet());

        // Act
        $productConcreteIds = array_map(
            'intval',
            $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer),
        );

        // Assert
        $this->assertCount(1, $productConcreteIds);
        $this->assertContains($productImageSetTransfer->getIdProduct(), $productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsFilteredByProductImageSetIdsWithEmptyResult(): void
    {
        // Arrange
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractEntity->getIdProductAbstract(),
        ]);

        $productImageFilterTransfer = (new ProductImageFilterTransfer())
            ->addProductImageSetId($productImageSetTransfer->getIdProductImageSet());

        // Act
        $productConcreteIds = $this->productImageFacade->getProductConcreteIds($productImageFilterTransfer);

        // Assert
        $this->assertEmpty($productConcreteIds);
    }
}
