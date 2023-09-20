<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group SaveProductImageTest
 * Add your own group annotations below this line
 */
class SaveProductImageTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testPersistProductImageShouldCreateImage(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall(static::URL_SMALL)
            ->setExternalUrlLarge(static::URL_LARGE);

        // Act
        $productImageTransfer = $this->productImageFacade->saveProductImage($productImageTransfer);

        // Assert
        $this->assertCreateImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return void
     */
    protected function assertCreateImage(ProductImageTransfer $productImageTransfer): void
    {
        $productImage = (new SpyProductImageQuery())
            ->filterByIdProductImage($productImageTransfer->getIdProductImage())
            ->findOne();

        $this->assertNotNull($productImage);
        $this->assertSame($productImageTransfer->getExternalUrlSmall(), $productImage->getExternalUrlSmall());
        $this->assertSame($productImageTransfer->getExternalUrlLarge(), $productImage->getExternalUrlLarge());
    }
}
