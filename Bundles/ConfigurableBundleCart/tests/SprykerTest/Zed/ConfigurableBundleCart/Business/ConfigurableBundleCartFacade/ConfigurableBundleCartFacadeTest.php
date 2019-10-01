<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleCart
 * @group Business
 * @group ConfigurableBundleCartFacade
 * @group Facade
 * @group ConfigurableBundleCartFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleCartFacadeTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleCart\ConfigurableBundleCartBusinessTester
     */
    protected $tester;
    
    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityForQuoteAdjustsBundleQuantity(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2, 1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Act
        $quoteTransfer = $this->tester->getFacade()->updateConfiguredBundleQuantityForQuote($quoteTransfer);

        // Assert
        $this->assertSame(2, $quoteTransfer->getItems()->offsetGet(0)->getConfiguredBundle()->getQuantity());
        $this->assertSame(2, $quoteTransfer->getItems()->offsetGet(1)->getConfiguredBundle()->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityForQuoteThrowsExceptionWhenQuantityPerSlotIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantityForQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityPerSlotForQuoteAdjustsQuantityPerSlot(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Act
        $quoteTransfer = $this->tester->getFacade()->updateConfiguredBundleQuantityPerSlotForQuote($quoteTransfer);

        // Assert
        $this->assertSame(2, $quoteTransfer->getItems()->offsetGet(0)->getConfiguredBundleItem()->getQuantityPerSlot());
        $this->assertSame(4, $quoteTransfer->getItems()->offsetGet(1)->getConfiguredBundleItem()->getQuantityPerSlot());
    }

    /**
     * @return void
     */
    public function testUpdateConfiguredBundleQuantityPerSlotForQuoteThrowsExceptionWhenQuantityIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateConfiguredBundleQuantityPerSlotForQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckConfiguredBundleQuantityInQuoteChecksItemQuantityCorrectnessWithConfiguredBundleQuantity(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2, 4),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Act
        $isValid = $this->tester->getFacade()->checkConfiguredBundleQuantityInQuote($quoteTransfer);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConfiguredBundleQuantityInQuoteThrowsExceptionWhenQuantityIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2, 4),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->checkConfiguredBundleQuantityInQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckConfiguredBundleQuantityInQuoteThrowsExceptionWhenQuantityPerSlotIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->checkConfiguredBundleQuantityInQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckConfiguredBundleQuantityInQuoteChecksItemQuantityCorrectnessWithConfiguredBundleQuantityWithWrongBundleQuantity(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2, 4),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(4),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Act
        $isValid = $this->tester->getFacade()->checkConfiguredBundleQuantityInQuote($quoteTransfer);

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConfiguredBundleQuantityInQuoteChecksItemQuantityCorrectnessWithConfiguredBundleQuantityWithWrongQuantityPerSlot(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 4,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1, 2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 8,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2, 8),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::QUANTITY => 6,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => null,
                ItemTransfer::CONFIGURED_BUNDLE => null,
            ])
            ->build();

        // Act
        $isValid = $this->tester->getFacade()->checkConfiguredBundleQuantityInQuote($quoteTransfer);

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @param int|null $quantity
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundle(?int $quantity = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid('FAKE_CONFIGURABLE_BUNDLE_UUID'))
            ->setGroupKey('FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY')
            ->setQuantity($quantity);
    }

    /**
     * @param string|null $slotUuid
     * @param int|null $quantityPerSlot
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemTransfer
     */
    protected function createConfiguredBundleItem(?string $slotUuid = null, ?int $quantityPerSlot = null): ConfiguredBundleItemTransfer
    {
        return (new ConfiguredBundleItemTransfer())
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid))
            ->setQuantityPerSlot($quantityPerSlot);
    }
}
