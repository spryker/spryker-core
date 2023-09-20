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
 * @group GetProductImagesSetCollectionByProductAbstractIdTest
 * Add your own group annotations below this line
 */
class GetProductImagesSetCollectionByProductAbstractIdTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractId(): void
    {
        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        // Assert
        $this->assertNotEmpty($productImageSetCollection);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdSortsImagesBySortOrderAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 3);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 1);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 2);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractSortedEntity->getIdProductAbstract(),
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
    public function testGetProductImagesSetCollectionByProductAbstractIdSortsImagesByIdProductImageSetToProductImageAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);

        // Act
        $productImageCollection = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractSortedEntity->getIdProductAbstract(),
        )[0]->getProductImages();

        // Assert
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue(
                $productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage,
            );
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }
}
