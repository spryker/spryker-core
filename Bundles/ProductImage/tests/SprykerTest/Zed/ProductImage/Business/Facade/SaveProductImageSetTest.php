<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group SaveProductImageSetTest
 * Add your own group annotations below this line
 */
class SaveProductImageSetTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testPersistProductImageSetShouldCreateImageSet(): void
    {
        // Arrange
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract());

        // Act
        $productImageSetTransfer = $this->productImageFacade->saveProductImageSet($productImageSetTransfer);

        // Assert
        $this->assertCreateImageSet($productImageSetTransfer);
    }

    /**
     * @return void
     */
    public function testPersistProductImageSetShouldPersistImageSetAndProductImages(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        // Act
        $productImageSetTransfer = $this->productImageFacade->saveProductImageSet($productImageSetTransfer);

        // Assert
        $this->assertCreateImageSet($productImageSetTransfer);
        $this->assertAbstractCreateImageForImageSet();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    protected function assertCreateImageSet(ProductImageSetTransfer $productImageSetTransfer): void
    {
        $productImage = (new SpyProductImageSetQuery())
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet())
            ->findOne();

        $this->assertNotNull($productImage);
        $this->assertSame(static::SET_NAME, $productImageSetTransfer->getName());
        $this->assertSame($this->productAbstractEntity->getIdProductAbstract(), $productImageSetTransfer->getIdProductAbstract());
    }
}
