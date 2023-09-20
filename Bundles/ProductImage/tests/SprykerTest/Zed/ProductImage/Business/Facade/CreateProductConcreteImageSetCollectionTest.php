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
 * @group CreateProductConcreteImageSetCollectionTest
 * Add your own group annotations below this line
 */
class CreateProductConcreteImageSetCollectionTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testCreateProductConcreteImageSetCollection(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->setName(static::SET_NAME)
            ->setIdProduct($this->productConcreteEntity->getIdProduct())
            ->addProductImage($productImageTransfer);

        $productConcreteTransfer->addImageSet($productImageSetTransfer);

        // Act
        $this->productImageFacade->createProductConcreteImageSetCollection(
            $productConcreteTransfer,
        );

        // Assert
        $this->assertConcreteCreateImageForImageSet();
    }
}
