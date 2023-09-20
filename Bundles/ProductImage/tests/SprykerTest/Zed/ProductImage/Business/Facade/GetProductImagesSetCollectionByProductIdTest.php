<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetProductImagesSetCollectionByProductIdTest
 * Add your own group annotations below this line
 */
class GetProductImagesSetCollectionByProductIdTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductId(): void
    {
        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductId(
            $this->productConcreteEntity->getIdProduct(),
        );

        // Assert
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
            $this->productConcreteSortedEntity->getIdProduct(),
        )[0]->getProductImages();

        // Assert
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
            $this->productConcreteSortedEntity->getIdProduct(),
        )[0]->getProductImages();

        // Assert
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage);
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }
}
