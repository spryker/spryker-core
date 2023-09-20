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
 * @group GetCombinedAbstractImageSetsTest
 * Add your own group annotations below this line
 */
class GetCombinedAbstractImageSetsTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetCombinedAbstractImageSets(): void
    {
        // Arrange, Act
        $imageSetTransfers = $this->productImageFacade->getCombinedAbstractImageSets(
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
