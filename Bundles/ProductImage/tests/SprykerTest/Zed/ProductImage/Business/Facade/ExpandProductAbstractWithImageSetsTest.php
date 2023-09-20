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
 * @group ExpandProductAbstractWithImageSetsTest
 * Add your own group annotations below this line
 */
class ExpandProductAbstractWithImageSetsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testExpandProductAbstractWithImageSets(): void
    {
        // Arrange
        $productAbstractTransfer = $this->createProductAbstractTransfer();

        //Act
        $productAbstractTransfer = $this->productImageFacade->expandProductAbstractWithImageSets(
            $productAbstractTransfer,
        );

        // Assert
        $this->assertNotEmpty($productAbstractTransfer->getImageSets());
        foreach ($productAbstractTransfer->getImageSets() as $imageSet) {
            $this->assertNotEmpty($imageSet->getProductImages());

            foreach ($imageSet->getProductImages() as $image) {
                $this->assertInstanceOf(ProductImageTransfer::class, $image);
            }
        }
    }
}
