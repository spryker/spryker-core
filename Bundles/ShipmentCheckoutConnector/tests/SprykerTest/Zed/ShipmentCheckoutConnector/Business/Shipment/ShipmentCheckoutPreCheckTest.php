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
use Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorFacade;

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
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                ->withMethod([ShipmentMethodTransfer::IS_ACTIVE => true])
            )
            ->withItem()
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $result = (new ShipmentCheckoutConnectorFacade())->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteShipmentIsDefinedAndMethodIsInactive(): void
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withMethod([ShipmentMethodTransfer::IS_ACTIVE => false])
            )
            ->withItem()
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $result = (new ShipmentCheckoutConnectorFacade())->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasMultipleShipmentsAndAllMethodsAreActive(): void
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                ->withShipment(
                    (new ShipmentBuilder())
                    ->withMethod([ShipmentMethodTransfer::IS_ACTIVE => true])
                )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                ->withAnotherShipment(
                    (new ShipmentBuilder())
                    ->withAnotherMethod([ShipmentMethodTransfer::IS_ACTIVE => true])
                )
            )
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $result = (new ShipmentCheckoutConnectorFacade())->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasTwoItemsWithSameShipmentAndMethodIsInactive(): void
    {
        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherMethod([ShipmentMethodTransfer::IS_ACTIVE => false]);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                ->withShipment($shipmentBuilder)
            )
            ->withAnotherItem(
                (new ItemBuilder())
                ->withShipment($shipmentBuilder)
            )
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $result = (new ShipmentCheckoutConnectorFacade())->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenQuoteHasTwoItemsWithDifferentShipmentsAndAllMethodsAreInactive(): void
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withMethod([ShipmentMethodTransfer::IS_ACTIVE => false])
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withAnotherShipment(
                        (new ShipmentBuilder())
                            ->withAnotherMethod([ShipmentMethodTransfer::IS_ACTIVE => false])
                    )
            )
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $result = (new ShipmentCheckoutConnectorFacade())->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($result);
    }
}
