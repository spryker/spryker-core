<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\ProductImage\Communication\Plugin\ProductPageSearch;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcretePageSearchBuilder;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductPageSearch\ProductImageAlternativeTextProductConcretePageDataExpanderPlugin;
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
 * @group ProductImageAlternativeTextProductConcretePageDataExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductImageAlternativeTextProductConcretePageDataExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_NAME = 'de_DE';

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
    public function testExpandProductConcretePageSearchTransferWithProductImageAlternativeTextsWithEnabledConfig(): void
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
                ProductImageTransfer::ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                ProductImageTransfer::ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
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
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchBuilder())->build();
        $productConcretePageSearchTransfer->setImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin = new ProductImageAlternativeTextProductConcretePageDataExpanderPlugin();
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->expand(
            new ProductConcreteTransfer(),
            $productConcretePageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            $smallAltTextTranslation,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            $largeAltTextTranslation,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_LARGE],
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImageAlternativeTextsWithDisabledConfig(): void
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
                ProductImageTransfer::ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                ProductImageTransfer::ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
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
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchBuilder())->build();
        $productConcretePageSearchTransfer->setImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin = new ProductImageAlternativeTextProductConcretePageDataExpanderPlugin();
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->expand(
            new ProductConcreteTransfer(),
            $productConcretePageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            $smallAltTextGlossaryKey,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            $largeAltTextGlossaryKey,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_LARGE],
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImageAlternativeTextsWithoutTranslationsWithEnabledConfig(): void
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
                ProductImageTransfer::ALT_TEXT_SMALL => $smallAltTextGlossaryKey,
                ProductImageTransfer::ALT_TEXT_LARGE => $largeAltTextGlossaryKey,
            ],
        ];
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchBuilder())->build();
        $productConcretePageSearchTransfer->setImages($images)
            ->setLocale($this->localeTransfer->getLocaleName());
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin = new ProductImageAlternativeTextProductConcretePageDataExpanderPlugin();
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->setBusinessFactory($productImageBusinessFactory)
            ->setConfig($productImageConfigMock);

        // Act
        $productImageAlternativeTextProductConcretePageDataExpanderPlugin->expand(
            new ProductConcreteTransfer(),
            $productConcretePageSearchTransfer,
        );

        // Assert
        $this->assertEquals(
            null,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_SMALL],
        );
        $this->assertEquals(
            null,
            $productConcretePageSearchTransfer->getImages()[0][ProductImageTransfer::ALT_TEXT_LARGE],
        );
    }
}
