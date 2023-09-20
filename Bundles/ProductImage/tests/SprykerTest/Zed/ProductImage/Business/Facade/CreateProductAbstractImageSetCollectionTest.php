<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group CreateProductAbstractImageSetCollectionTest
 * Add your own group annotations below this line
 */
class CreateProductAbstractImageSetCollectionTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testCreateProductAbstractImageSetCollection(): void
    {
        // Arrange
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->addProductImage($productImageTransfer);

        $productAbstractTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductAbstractImageSetCollection(
            $productAbstractTransfer,
        );

        // Assert
        $this->assertAbstractCreateImageForImageSet();
    }
}
