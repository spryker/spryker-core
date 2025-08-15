<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductImage\ProductImageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetProductImagesSetCollectionByProductAbstractIdWithAlternatiiveTextsTest
 * Add your own group annotations below this line
 */
class GetProductImagesSetCollectionByProductAbstractIdWithAlternatiiveTextsTest extends AbstractProductImageFacadeTest
{
    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected LocaleTransfer $localeTransferDe;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected LocaleTransfer $localeTransferEn;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->localeTransferDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]);
        $this->localeTransferEn = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_EN]);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdWithLocalizedImageSet(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $productImageFacade = new ProductImageFacade();
        $productImageFacade->setFactory($productImageBusinessFactory);
        $smallAltTextTranslation = 'small';
        $largeAltTextTranslation = 'large';
        $this->tester->haveTranslations(
            [
                $this->image->getAltTextSmall() => [static::LOCALE_NAME_DE => $smallAltTextTranslation],
                $this->image->getAltTextLarge() => [static::LOCALE_NAME_DE => $largeAltTextTranslation],
            ],
        );

        // Act
        $productImageSetCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        // Assert
        $productImageSetTransfer = $this->findProductImageSetByIdLocale(
            $productImageSetCollection,
            $this->localeTransferDe->getIdLocale(),
        );
        $productImageTranslationTransfer = $productImageSetTransfer->getProductImages()
            ->getIterator()
            ->current()
            ->getTranslations()
            ->getIterator()
            ->current();
        $this->assertSame($productImageTranslationTransfer->getAltTextSmall(), $smallAltTextTranslation);
        $this->assertSame($productImageTranslationTransfer->getAltTextLarge(), $largeAltTextTranslation);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdWithDefaultImageSet(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $productImageFacade = new ProductImageFacade();
        $productImageFacade->setFactory($productImageBusinessFactory);
        $smallAltTextGlossaryKey = $this->image->getAltTextSmall();
        $largeAltTextGlossaryKey = $this->image->getAltTextLarge();
        $translations = [
            static::LOCALE_NAME_DE => [
                $smallAltTextGlossaryKey => 'smallDe',
                $largeAltTextGlossaryKey => 'largeDe',
            ],
            static::LOCALE_NAME_EN => [
                $smallAltTextGlossaryKey => 'smallEn',
                $largeAltTextGlossaryKey => 'largeEn',
            ],
        ];
        $this->tester->haveTranslations(
            [
                $smallAltTextGlossaryKey => [
                    static::LOCALE_NAME_DE => $translations[static::LOCALE_NAME_DE][$smallAltTextGlossaryKey],
                    static::LOCALE_NAME_EN => $translations[static::LOCALE_NAME_EN][$smallAltTextGlossaryKey],
                ],
                $largeAltTextGlossaryKey => [
                    static::LOCALE_NAME_DE => $translations[static::LOCALE_NAME_DE][$largeAltTextGlossaryKey],
                    static::LOCALE_NAME_EN => $translations[static::LOCALE_NAME_EN][$largeAltTextGlossaryKey],
                ],
            ],
        );

        // Act
        $productImageSetCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        // Assert
        $productImageSetTransfer = $this->findProductImageSetByIdLocale($productImageSetCollection, null);
        // Assert
        $productImageTranslationTransfers = $productImageSetTransfer->getProductImages()
            ->getIterator()
            ->current()
            ->getTranslations();

        foreach ($productImageTranslationTransfers as $productImageTranslationTransfer) {
            $localeName = $productImageTranslationTransfer->getLocale()->getLocaleName();
            $this->assertSame($productImageTranslationTransfer->getAltTextSmall(), $translations[$localeName][$smallAltTextGlossaryKey]);
            $this->assertSame($productImageTranslationTransfer->getAltTextLarge(), $translations[$localeName][$largeAltTextGlossaryKey]);
        }
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdWithoutTranslations(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $productImageFacade = new ProductImageFacade();
        $productImageFacade->setFactory($productImageBusinessFactory);

        // Act
        $productImageSetCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        // Assert
        $productImageSetTransfer = $this->findProductImageSetByIdLocale($productImageSetCollection, null);
        // Assert
        $productImageTranslationTransfers = $productImageSetTransfer->getProductImages()
            ->getIterator()
            ->current()
            ->getTranslations();

        $this->assertEmpty($productImageTranslationTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductImagesSetCollectionByProductAbstractIdWithLocalizedImageSetWithDisabledConfig(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(false);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $productImageFacade = new ProductImageFacade();
        $productImageFacade->setFactory($productImageBusinessFactory);
        $smallAltTextTranslation = 'small';
        $largeAltTextTranslation = 'large';
        $this->tester->haveTranslations(
            [
                $this->image->getAltTextSmall() => [static::LOCALE_NAME_DE => $smallAltTextTranslation],
                $this->image->getAltTextLarge() => [static::LOCALE_NAME_DE => $largeAltTextTranslation],
            ],
        );

        // Act
        $productImageSetCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId(
            $this->productAbstractEntity->getIdProductAbstract(),
        );

        // Assert
        $productImageSetTransfer = $this->findProductImageSetByIdLocale(
            $productImageSetCollection,
            $this->localeTransferDe->getIdLocale(),
        );
        $productImageTranslationTransfers = $productImageSetTransfer->getProductImages()
            ->getIterator()
            ->current()
            ->getTranslations();

        $this->assertEmpty($productImageTranslationTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetCollection
     * @param int|null $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    protected function findProductImageSetByIdLocale(array $productImageSetCollection, ?int $idLocale): ?ProductImageSetTransfer
    {
        foreach ($productImageSetCollection as $productImageSetTransfer) {
            if (
                (!$productImageSetTransfer->getLocale() && !$idLocale)
                || ($productImageSetTransfer->getLocale() && $productImageSetTransfer->getLocale()->getIdLocale() === $idLocale)
            ) {
                return $productImageSetTransfer;
            }
        }

        return null;
    }
}
