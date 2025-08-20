<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\ProductImage\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPageSearchBuilder;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductPageSearch\ProductImageAlternativeTextProductPageDataExpanderPlugin;
use Spryker\Zed\ProductImage\ProductImageConfig;
use SprykerTest\Zed\ProductImage\ProductImageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Communication
 * @group Plugin
 * @group ProductPageSearch
 * @group ProductImageAlternativeTextProductPageDataExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductImageAlternativeTextProductPageDataExpanderPluginTest extends Unit
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
     * @var \SprykerTest\Zed\ProductImage\ProductImageCommunicationTester
     */
    protected ProductImageCommunicationTester $tester;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected LocaleTransfer $localeTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandProductPageSearchTransferWithProductImageAlternativeTextsWithEnabledConfig(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $smallAltTextGlossaryKey = 'product_image.small_alt_text_key';
        $smallAltTextTranslation = 'small';
        $largeAltTextGlossaryKey = 'product_image.large_alt_text_key';
        $largeAltTextTranslation = 'large';
        $images = [
            [
                static::KEY_ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                static::KEY_ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
            ],
        ];
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $smallAltTextGlossaryKey,
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $smallAltTextTranslation,
            ],
        ]);
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $largeAltTextGlossaryKey,
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $largeAltTextTranslation,
            ],
        ]);
        $productAbstractPageSearchTransfer = (new ProductPageSearchBuilder())->build();
        $productAbstractPageSearchTransfer->setProductImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductPageDataExpanderPlugin = new ProductImageAlternativeTextProductPageDataExpanderPlugin();
        $productImageAlternativeTextProductPageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductPageDataExpanderPlugin->expandProductPageData(
            [],
            $productAbstractPageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            $smallAltTextTranslation,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            $largeAltTextTranslation,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_LARGE],
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageSearchTransferWithProductImageAlternativeTextsWithDisabledConfig(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(false);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $smallAltTextGlossaryKey = 'product_image.small_alt_text_key';
        $smallAltTextTranslation = 'small';
        $largeAltTextGlossaryKey = 'product_image.large_alt_text_key';
        $largeAltTextTranslation = 'large';
        $images = [
            [
                static::KEY_ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                static::KEY_ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
            ],
        ];
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $smallAltTextGlossaryKey,
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $smallAltTextTranslation,
            ],
        ]);
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => $largeAltTextGlossaryKey,
            KeyTranslationTransfer::LOCALES => [
                $this->localeTransfer->getLocaleName() => $largeAltTextTranslation,
            ],
        ]);
        $productAbstractPageSearchTransfer = (new ProductPageSearchBuilder())->build();
        $productAbstractPageSearchTransfer->setProductImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductPageDataExpanderPlugin = new ProductImageAlternativeTextProductPageDataExpanderPlugin();
        $productImageAlternativeTextProductPageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductPageDataExpanderPlugin->expandProductPageData(
            [],
            $productAbstractPageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            $smallAltTextGlossaryKey,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            $largeAltTextGlossaryKey,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_LARGE],
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageSearchTransferWithProductImageAlternativeTextsWithoutTranslationsWithEnabledConfig(): void
    {
        // Arrange
        $productImageConfigMock = $this->getMockBuilder(ProductImageConfig::class)
            ->onlyMethods(['isProductImageAlternativeTextEnabled'])
            ->getMock();
        $productImageConfigMock->method('isProductImageAlternativeTextEnabled')->willReturn(true);
        $productImageBusinessFactory = new ProductImageBusinessFactory();
        $productImageBusinessFactory->setConfig($productImageConfigMock);
        $smallAltTextGlossaryKey = 'product_image.small_alt_text_key';
        $largeAltTextGlossaryKey = 'product_image.large_alt_text_key';
        $images = [
            [
                static::KEY_ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                static::KEY_ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
            ],
        ];
        $productAbstractPageSearchTransfer = (new ProductPageSearchBuilder())->build();
        $productAbstractPageSearchTransfer->setProductImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductPageDataExpanderPlugin = new ProductImageAlternativeTextProductPageDataExpanderPlugin();
        $productImageAlternativeTextProductPageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductPageDataExpanderPlugin->expandProductPageData(
            [],
            $productAbstractPageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            null,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            null,
            $productAbstractPageSearchTransfer->getProductImages()[0][static::KEY_ALT_TEXT_LARGE],
        );
    }
}
