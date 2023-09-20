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
 * @group GetCombinedConcreteImageSetsTest
 * Add your own group annotations below this line
 */
class GetCombinedConcreteImageSetsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetCombinedConcreteImageSets(): void
    {
        // Arrange, Act
        $imageSetTransfers = $this->productImageFacade->getCombinedConcreteImageSets(
            $this->productConcreteEntity->getIdProduct(),
            $this->productConcreteEntity->getFkProductAbstract(),
            static::ID_LOCALE_DE,
        );

        // Assert
        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME]);
        $this->assertNotEmpty($imageSetTransfers[static::SET_NAME_DE]);

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $defaultImageSetTransfer */
        $defaultImageSetTransfer = $imageSetTransfers[static::SET_NAME];

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $localizedImageSetTransfer */
        $localizedImageSetTransfer = $imageSetTransfers[static::SET_NAME_DE];

        $defaultProductImages = $defaultImageSetTransfer->getProductImages();
        $localizedProductImages = $localizedImageSetTransfer->getProductImages();

        $this->assertSame(1, count($defaultProductImages));
        $this->assertSame(1, count($localizedProductImages));
    }
}
