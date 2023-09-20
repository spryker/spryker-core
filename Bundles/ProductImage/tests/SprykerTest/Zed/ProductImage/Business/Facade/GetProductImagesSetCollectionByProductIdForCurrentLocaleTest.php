<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetProductImagesSetCollectionByProductIdForCurrentLocaleTest
 * Add your own group annotations below this line
 */
class GetProductImagesSetCollectionByProductIdForCurrentLocaleTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocale(): void
    {
        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct(),
        );

        // Assert
        $this->assertNotEmpty($productImageSetCollection);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocaleReturnsProductImagesSetWithProperLocale(): void
    {
        // Arrange
        $this->tester->createProductImageSet(
            static::SET_NAME_DE,
            null,
            $this->productConcreteEntity->getIdProduct(),
            static::ID_LOCALE_DE,
        );

        if ($this->tester->isDynamicStoreEnabled() === false) {
            Store::getInstance()->setCurrentLocale(static::LOCALE_DE_DE);
        }

        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct(),
        );

        // Assert
        foreach ($productImageSetCollection as $productImageSetTransfer) {
            $this->assertTrue($productImageSetTransfer->getLocale()->getLocaleName() === static::LOCALE_DE_DE);
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductIdForCurrentLocaleReturnsDefaultProductImagesSets(): void
    {
        // Arrange
        $this->tester->createProductImageSet(
            static::SET_NAME_EN,
            null,
            $this->productConcreteEntity->getIdProduct(),
            static::ID_LOCALE_EN,
        );

        if ($this->tester->isDynamicStoreEnabled() === false) {
            Store::getInstance()->setCurrentLocale(static::LOCALE_DE_DE);
        }

        // Act
        $productImageSetCollection = $this->productImageFacade->getProductImagesSetCollectionByProductIdForCurrentLocale(
            $this->productConcreteEntity->getIdProduct(),
        );

        // Assert
        foreach ($productImageSetCollection as $productImageSetTransfer) {
            $this->assertNull($productImageSetTransfer->getLocale());
        }
    }
}
