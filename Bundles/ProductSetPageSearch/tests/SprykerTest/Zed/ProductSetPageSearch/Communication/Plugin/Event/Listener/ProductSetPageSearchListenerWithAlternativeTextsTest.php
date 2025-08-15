<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchBusinessFactory;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetDataPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductImageFacadeBridge;
use Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchDependencyProvider;
use SprykerTest\Zed\ProductSetPageSearch\ProductSetPageSearchCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSetPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductSetPageSearchListenerWithAlternativeTextsTest
 * Add your own group annotations below this line
 */
class ProductSetPageSearchListenerWithAlternativeTextsTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_NAME = 'de_DE';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_SMALL = 'alt_text_small';

    /**
     * @var string
     */
    protected const KEY_ALT_TEXT_LARGE = 'alt_text_large';

    /**
     * @var \SprykerTest\Zed\ProductSetPageSearch\ProductSetPageSearchCommunicationTester
     */
    protected ProductSetPageSearchCommunicationTester $tester;

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

        $this->localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME]);
    }

    /**
     * @return void
     */
    public function testProductSetPageSearchListenerStoreDataWithAlternativeTexts(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => 'product_set_product_image.small_alt_text_key',
                ProductImageTransfer::ALT_TEXT_LARGE => 'product_set_product_image.large_alt_text_key',
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
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
            ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetPageSearchToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetPageSearchBusinessFactory::class,
        );
        $productSetDataPageSearchListener = new ProductSetDataPageSearchListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetDataPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertSame($smallAltTextTranslation, $productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertSame($largeAltTextTranslation, $productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }

    /**
     * @return void
     */
    public function testProductSetPageSearchListenerStoreDataWithAlternativeTextsWithoutTranslations(): void
    {
        // Arrange
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => 'product_set_product_image.small_alt_text_key',
                ProductImageTransfer::ALT_TEXT_LARGE => 'product_set_product_image.large_alt_text_key',
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
        $productImageFacadeMock = $this->getMockBuilder(ProductImageFacade::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageFacadeMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $this->tester->setDependency(
            ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetPageSearchToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetPageSearchBusinessFactory::class,
        );
        $productSetDataPageSearchListener = new ProductSetDataPageSearchListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetDataPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertNull($productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }

    /**
     * @return void
     */
    public function testProductSetPageSearchListenerStoreDataWithAlternativeTextsWithDisabledConfig(): void
    {
        // Arrange
        $altTextSmallKey = 'product_set_product_image.small_alt_text_key';
        $altTextLargeKey = 'product_set_product_image.large_alt_text_key';
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([
                ProductImageTransfer::ALT_TEXT_SMALL => $altTextSmallKey,
                ProductImageTransfer::ALT_TEXT_LARGE => $altTextLargeKey,
            ])
            ->build();
        $productSetTransfer = $this->tester->createProductSetWithProductImages([$productImageTransfer], $this->localeTransfer);
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
            ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_IMAGE,
            new ProductSetPageSearchToProductImageFacadeBridge($productImageFacadeMock),
            ProductSetPageSearchBusinessFactory::class,
        );
        $productSetDataPageSearchListener = new ProductSetDataPageSearchListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ]),
        ];

        // Act
        $productSetDataPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE);

        // Assert
        $productSetImages = $this->tester->getProductSetImages($productSetTransfer->getIdProductSet());
        $this->assertSame($altTextSmallKey, $productSetImages[0][static::KEY_ALT_TEXT_SMALL]);
        $this->assertSame($altTextLargeKey, $productSetImages[0][static::KEY_ALT_TEXT_LARGE]);
    }
}
