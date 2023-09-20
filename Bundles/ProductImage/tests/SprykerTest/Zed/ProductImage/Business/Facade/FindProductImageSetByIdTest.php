<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group FindProductImageSetByIdTest
 * Add your own group annotations below this line
 */
class FindProductImageSetByIdTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetProductImagesSetById(): void
    {
        // Act
        $productImageSetTransfer = $this->productImageFacade->findProductImageSetById(
            $this->imageSetAbstract->getIdProductImageSet(),
        );

        // Assert
        $this->assertNotEmpty($productImageSetTransfer);
        $this->assertCount(1, $productImageSetTransfer->getProductImages());
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetByIdWithoutImages(): void
    {
        // Arrange
        $imageSetEntity = new SpyProductImageSet();
        $imageSetEntity
            ->setName(static::SET_NAME)
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->setFkProduct(null)
            ->setFkLocale(null)
            ->save();

        // Act
        $productImageSetTransfer = $this->productImageFacade->findProductImageSetById(
            $imageSetEntity->getIdProductImageSet(),
        );

        // Assert
        $this->assertNotEmpty($productImageSetTransfer);
        $this->assertCount(0, $productImageSetTransfer->getProductImages());
    }

    /**
     * @return void
     */
    public function testFindProductImageSetByIdSortsImagesBySortOrderAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 3);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 1);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 2);

        // Act
        $productImageCollection = $this->productImageFacade->findProductImageSetById(
            $this->imageSetSortedAbstract->getIdProductImageSet(),
        )->getProductImages();

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
    public function testFindProductImageSetByIdSortsImagesByidProductImageSetToProductImageAsc(): void
    {
        // Arrange
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);
        $this->tester->createProductImageSetToProductImage($this->imageSetSortedAbstract->getIdProductImageSet(), 0);

        // Act
        $productImageCollection = $this->productImageFacade->findProductImageSetById(
            $this->imageSetSortedAbstract->getIdProductImageSet(),
        )->getProductImages();

        // Assert
        $idProductImageSetToProductImage = 0;
        foreach ($productImageCollection as $productImageTransfer) {
            $this->assertTrue($productImageTransfer->getIdProductImageSetToProductImage() > $idProductImageSetToProductImage);
            $idProductImageSetToProductImage = $productImageTransfer->getIdProductImageSetToProductImage();
        }
    }
}
