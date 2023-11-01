<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductBundleCartsRestApi\Plugin\ShipmentsRestApi;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Glue\ProductBundleCartsRestApi\Plugin\ShipmentsRestApi\CopyShipmentToProductBundleQuoteItemExpanderPlugin;
use SprykerTest\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductBundleCartsRestApi
 * @group Plugin
 * @group ShipmentsRestApi
 * @group CopyShipmentToProductBundleQuoteItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class CopyShipmentToProductBundleQuoteItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const BUNDLE_ITEM_IDENTIFIER = 'BUNDLE_ITEM_IDENTIFIER';

    /**
     * @var string
     */
    protected const FAKE_BUNDLE_ITEM_IDENTIFIER = 'FAKE_BUNDLE_ITEM_IDENTIFIER';

    /**
     * @var \SprykerTest\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiPluginTester
     */
    protected ProductBundleCartsRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testDoNothingForQuoteWithoutProductBundles(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentBuilder())->withShippingAddress()->build();

        $itemTransfers = [
            (new ItemTransfer())->setShipment($shipmentTransfer),
            (new ItemTransfer())->setShipment($shipmentTransfer),
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer($itemTransfers);

        // Act
        $quoteTransfer = (new CopyShipmentToProductBundleQuoteItemExpanderPlugin())->expandQuoteItems($quoteTransfer);

        // Assert
        $this->assertEmpty($quoteTransfer->getBundleItems());
    }

    /**
     * @return void
     */
    public function testDoNothingForQuoteWithoutItemShipments(): void
    {
        // Arrange
        $itemTransfers = [
            (new ItemTransfer())->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $bundleItemTransfers = [
            (new ItemTransfer())->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer($itemTransfers, $bundleItemTransfers);

        // Act
        $quoteTransfer = (new CopyShipmentToProductBundleQuoteItemExpanderPlugin())->expandQuoteItems($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getBundleItems()->getIterator()->current()->getShipment());
    }

    /**
     * @return void
     */
    public function testDoNothingForBundleItemsWithoutRelatedItems(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentBuilder())->withShippingAddress()->build();

        $itemTransfers = [
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $bundleItemTransfers = [
            (new ItemTransfer())->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER),
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer($itemTransfers, $bundleItemTransfers);

        // Act
        $quoteTransfer = (new CopyShipmentToProductBundleQuoteItemExpanderPlugin())->expandQuoteItems($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getBundleItems()->getIterator()->current()->getShipment());
    }

    /**
     * @return void
     */
    public function testCopyShipmentFromItemLevelToBundleItems(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentBuilder())->withShippingAddress()->build();

        $itemTransfers = [
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $bundleItemTransfers = [
            (new ItemTransfer())->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer($itemTransfers, $bundleItemTransfers);

        // Act
        $quoteTransfer = (new CopyShipmentToProductBundleQuoteItemExpanderPlugin())->expandQuoteItems($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ShipmentTransfer $bundleShipment */
        $bundleShipment = $quoteTransfer->getBundleItems()->getIterator()->current()->getShipment();

        $this->assertSame($shipmentTransfer->getShipmentSelection(), $bundleShipment->getShipmentSelection());
        $this->assertSame($shipmentTransfer->getRequestedDeliveryDate(), $bundleShipment->getRequestedDeliveryDate());
        $this->assertSame($shipmentTransfer->getShippingAddress()->toArray(), $bundleShipment->getShippingAddress()->toArray());
    }

    /**
     * @return void
     */
    public function testReplaceShipmentForProductBundle(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentBuilder())->withShippingAddress()->build();
        $bundleShipmentTransfer = (new ShipmentBuilder())->withShippingAddress()->build();

        $itemTransfers = [
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())->setShipment($shipmentTransfer)->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $bundleItemTransfers = [
            (new ItemTransfer())->setShipment($bundleShipmentTransfer)->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ];

        $quoteTransfer = $this->tester->createQuoteTransfer($itemTransfers, $bundleItemTransfers);

        // Act
        $quoteTransfer = (new CopyShipmentToProductBundleQuoteItemExpanderPlugin())->expandQuoteItems($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ShipmentTransfer $bundleShipment */
        $bundleShipment = $quoteTransfer->getBundleItems()->getIterator()->current()->getShipment();

        $this->assertSame($shipmentTransfer->getShipmentSelection(), $bundleShipment->getShipmentSelection());
        $this->assertSame($shipmentTransfer->getRequestedDeliveryDate(), $bundleShipment->getRequestedDeliveryDate());
        $this->assertSame($shipmentTransfer->getShippingAddress()->toArray(), $bundleShipment->getShippingAddress()->toArray());
    }
}
