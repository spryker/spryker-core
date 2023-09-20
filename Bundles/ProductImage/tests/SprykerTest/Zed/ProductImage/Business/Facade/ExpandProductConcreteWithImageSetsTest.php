<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductImageTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group ExpandProductConcreteWithImageSetsTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteWithImageSetsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testExpandProductConcreteWithImageSets(): void
    {
        // Arrange
        $productConcreteTransfer = $this->createProductConcreteTransfer();

        // Act
        $productConcreteTransfer = $this->productImageFacade->expandProductConcreteWithImageSets(
            $productConcreteTransfer,
        );

        // Assert
        $this->assertNotEmpty($productConcreteTransfer->getImageSets());
        foreach ($productConcreteTransfer->getImageSets() as $imageSet) {
            $this->assertNotEmpty($imageSet->getProductImages());

            foreach ($imageSet->getProductImages() as $image) {
                $this->assertInstanceOf(ProductImageTransfer::class, $image);
            }
        }
    }
}
