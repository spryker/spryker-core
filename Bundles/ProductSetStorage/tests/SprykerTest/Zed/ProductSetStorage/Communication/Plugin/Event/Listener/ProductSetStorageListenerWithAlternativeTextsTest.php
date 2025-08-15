<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductSetStorage\Business\ProductSetStorageBusinessFactory;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageSetStorageListener;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToProductImageFacadeBridge;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;
use SprykerTest\Zed\ProductSetStorage\ProductSetStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSetStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductSetStorageListenerWithAlternativeTextsTest
 * Add your own group annotations below this line
 */
class ProductSetStorageListenerWithAlternativeTextsTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_SMALL = 'alt_text_small';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_LARGE = 'alt_text_large';

    /**
     * @var \SprykerTest\Zed\ProductSetStorage\ProductSetStorageCommunicationTester
     */
    protected ProductSetStorageCommunicationTester $tester;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected LocaleTransfer $localeTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => 'de_DE']);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageSetStorageListenerWithAltTexts(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => 'product_set_product_image.small_alt_text_key',
                ProductImageTransfer::ALT_TEXT_LARGE => 'product_set_product_image.large_alt_text_key',
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
        $this->tester->deleteProductSetStorageByFkProductSet($productSetTransfer->getIdProductSet());
        $smallAltTextTranslation = 'small';
        $largeAltTextTranslation = 'large';
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $productImageTransfer->getAltTextSmall(),
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $smallAltTextTranslation,
            ],
        ]);
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $productImageTransfer->getAltTextLarge(),
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $largeAltTextTranslation,
            ],
        ]);
        $productImageFacadeMock = $this->getMockBuilder(ProductImageFacade::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageFacadeMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $this->tester->setDependency(
            ProductSetStorageDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetStorageToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetStorageBusinessFactory::class,
        );

        $productSetProductImageSetStorageListener = new ProductSetProductImageSetStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetProductImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertSame($smallAltTextTranslation, $productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertSame($largeAltTextTranslation, $productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageSetStorageListenerWithAltTextsWithoutTranslations(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => 'product_set_product_image.small_alt_text_key',
                ProductImageTransfer::ALT_TEXT_LARGE => 'product_set_product_image.large_alt_text_key',
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
        $this->tester->deleteProductSetStorageByFkProductSet($productSetTransfer->getIdProductSet());
        $productImageFacadeMock = $this->getMockBuilder(ProductImageFacade::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageFacadeMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $this->tester->setDependency(
            ProductSetStorageDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetStorageToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetStorageBusinessFactory::class,
        );
        $productSetProductImageSetStorageListener = new ProductSetProductImageSetStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetProductImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageSetStorageListenerWithAltTextsWithDisabledConfig(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => 'product_set_product_image.small_alt_text_key',
                ProductImageTransfer::ALT_TEXT_LARGE => 'product_set_product_image.large_alt_text_key',
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
        $this->tester->deleteProductSetStorageByFkProductSet($productSetTransfer->getIdProductSet());
        $smallAltTextTranslation = 'small';
        $largeAltTextTranslation = 'large';
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $productImageTransfer->getAltTextSmall(),
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $smallAltTextTranslation,
            ],
        ]);
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $productImageTransfer->getAltTextLarge(),
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $largeAltTextTranslation,
            ],
        ]);
        $productImageFacadeMock = $this->getMockBuilder(ProductImageFacade::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageFacadeMock->method('isProductImageAlternativeTextEnabled')->willReturn(false);
        $this->tester->setDependency(
            ProductSetStorageDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetStorageToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetStorageBusinessFactory::class,
        );

        $productSetProductImageSetStorageListener = new ProductSetProductImageSetStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetProductImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }
}
