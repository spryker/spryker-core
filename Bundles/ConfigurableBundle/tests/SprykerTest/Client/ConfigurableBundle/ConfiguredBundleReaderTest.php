<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ConfigurableBundle;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\ConfigurableBundle\Reader\ConfiguredBundleReader;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ConfigurableBundle
 * @group ConfiguredBundleReaderTest
 * Add your own group annotations below this line
 */
class ConfiguredBundleReaderTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_2';

    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ConfigurableBundle\Reader\ConfiguredBundleReader
     */
    protected $configuredBundleReaderMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configuredBundleReaderMock = $this->createConfiguredBundleReaderMock();
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteExtractsConfiguredBundlesFromQuote(): void
    {
        // Arrange
        $firstGroupKey = uniqid('', true);
        $secondGroupKey = uniqid('', true);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, $firstGroupKey),
            ])
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, $firstGroupKey),
            ])
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, $secondGroupKey),
            ])
            ->build();

        // Act
        $configuredBundleCollectionTransfer = $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(2, $configuredBundleCollectionTransfer->getConfiguredBundles());
        $this->assertCount(2, $configuredBundleCollectionTransfer->getConfiguredBundles()->offsetGet(0)->getItems());
        $this->assertCount(1, $configuredBundleCollectionTransfer->getConfiguredBundles()->offsetGet(1)->getItems());
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteWithoutConfiguredBundles(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->build();

        // Act
        $configuredBundleCollectionTransfer = $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(0, $configuredBundleCollectionTransfer->getConfiguredBundles());
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteIgnoresItemWithoutConfiguredBundleProperty(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
            ])
            ->build();

        // Act
        $configuredBundleCollectionTransfer = $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(0, $configuredBundleCollectionTransfer->getConfiguredBundles());
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteIgnoresItemWithoutConfiguredBundleItemProperty(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true)),
            ])
            ->build();

        // Act
        $configuredBundleCollectionTransfer = $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);

        // Assert
        $this->assertCount(0, $configuredBundleCollectionTransfer->getConfiguredBundles());
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenGroupKeyNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, null),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenQuantityNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true))
                    ->setQuantity(null),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true))
                    ->setTemplate(null),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateUuidNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(null, uniqid('', true)),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateNameNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
                    ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1)->setName(null)),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenSlotNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => new ConfiguredBundleItemTransfer(),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true)),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfiguredBundlesFromQuoteThrowsExceptionWhenSlotUuidNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true)),
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->configuredBundleReaderMock->getConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createConfiguredBundleReaderMock(): MockObject
    {
        return $this->getMockBuilder(ConfiguredBundleReader::class)
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @param string|null $templateUuid
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundle(?string $templateUuid = null, ?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid($templateUuid))
            ->setGroupKey($groupKey);
    }

    /**
     * @param string|null $slotUuid
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemTransfer
     */
    protected function createConfiguredBundleItem(?string $slotUuid = null): ConfiguredBundleItemTransfer
    {
        return (new ConfiguredBundleItemTransfer())
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid));
    }
}
