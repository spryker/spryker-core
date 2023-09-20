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
 * @group ExpandProductConcreteTransfersWithImageSetsTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteTransfersWithImageSetsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testExpandProductConcreteTransfersWithImageSetsSuccessful(): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->createProductConcreteTransfer();
        $productConcreteTransfer2 = $this->createProductConcreteTransfer();

        // Act
        $productConcreteTransfers = $this->productImageFacade->expandProductConcreteTransfersWithImageSets(
            [$productConcreteTransfer1, $productConcreteTransfer2],
        );

        // Assert
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertNotEmpty($productConcreteTransfer->getImageSets());
            foreach ($productConcreteTransfer->getImageSets() as $imageSet) {
                $this->assertNotEmpty($imageSet->getProductImages());

                foreach ($imageSet->getProductImages() as $image) {
                    $this->assertInstanceOf(ProductImageTransfer::class, $image);
                }
            }
        }
    }
}
