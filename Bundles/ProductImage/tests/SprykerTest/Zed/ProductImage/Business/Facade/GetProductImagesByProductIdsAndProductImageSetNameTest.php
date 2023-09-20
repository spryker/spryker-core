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
 * @group GetProductImagesByProductIdsAndProductImageSetNameTest
 * Add your own group annotations below this line
 */
class GetProductImagesByProductIdsAndProductImageSetNameTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetDefaultProductImagesByProductIdsReturnsImages(): void
    {
        // Arrange
        $productIds = [$this->productConcreteEntity->getIdProduct()];

        // Act
        $productImagesCollection = $this->productImageFacade->getProductImagesByProductIdsAndProductImageSetName($productIds, static::SET_NAME);

        // Assert
        $this->assertCount(count($productIds), $productImagesCollection);
        $this->assertEquals($productIds, array_keys($productImagesCollection));
        $this->assertNotEmpty($productImagesCollection[$this->productConcreteEntity->getIdProduct()]);
    }
}
