<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCheckoutConnector
 * @group Business
 * @group Shipment
 * @group ShipmentCheckoutPreCheckTest
 * Add your own group annotations below this line
 */
class ShipmentCheckoutPreCheckTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteShipmentIsDefinedAndMethodIsActive(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment(new ShipmentBuilder())
            ->withItem()
            ->build();

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getShipment(), true);
        $quoteTransfer->setShipment($shipmentTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        // Act
        $result = $this->tester->getFacade()->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result, 'The shipment check should return true, false given');
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteShipmentIsDefinedAndMethodIsInactive(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment(new ShipmentBuilder())
            ->withItem()
            ->build();

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getShipment(), false);
        $quoteTransfer->setShipment($shipmentTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        // Act
        $result = $this->tester->getFacade()->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result, 'The shipment check should return false, true given');
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasMultipleShipmentsAndAllMethodsAreActive(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                ->withShipment(new ShipmentBuilder())
            )
            ->withAnotherItem(
                (new ItemBuilder())
                ->withAnotherShipment(new ShipmentBuilder())
            )
            ->build();

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[0]->getShipment(), true);
        $quoteTransfer->getItems()[0]->setShipment($shipmentTransfer);

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[1]->getShipment(), true);
        $quoteTransfer->getItems()[1]->setShipment($shipmentTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        // Act
        $result = $this->tester->getFacade()->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result, 'The shipment check should return true, false given');
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasTwoItemsWithSameShipmentAndMethodIsInactive(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(new ShipmentBuilder())
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withShipment(new ShipmentBuilder())
            )
            ->build();

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[0]->getShipment(), false);
        $quoteTransfer->getItems()[0]->setShipment($shipmentTransfer);

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[1]->getShipment(), false);
        $quoteTransfer->getItems()[1]->setShipment($shipmentTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        // Act
        $result = $this->tester->getFacade()->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result, 'The shipment check should return false, true given');
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasTwoItemsWithDifferentShipmentsAndAllMethodsAreInactive(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(new ShipmentBuilder())
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withAnotherShipment(new ShipmentBuilder())
            )
            ->build();

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[0]->getShipment(), false);
        $quoteTransfer->getItems()[0]->setShipment($shipmentTransfer);

        $shipmentTransfer = $this->addShipmentMethodTransfer($quoteTransfer->getItems()[1]->getShipment(), false);
        $quoteTransfer->getItems()[1]->setShipment($shipmentTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        // Act
        $result = $this->tester->getFacade()->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result, 'The shipment check should return false, true given');
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function addShipmentMethodTransfer(ShipmentTransfer $shipmentTransfer, bool $isActive): ShipmentTransfer
    {
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([ShipmentMethodTransfer::IS_ACTIVE => $isActive]);

        return $shipmentTransfer->setMethod($shipmentMethodTransfer);
    }
}
