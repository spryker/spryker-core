<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCheckoutConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCheckoutConnector
 * @group Business
 * @group Facade
 * @group ShipmentCheckoutConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentCheckoutConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckShipmentWhenNonActiveUsedShouldAddError()
    {
        $shipmentCheckoutConnectorFacade = $this->createShipmentCheckoutConnectorFacade();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod()
            ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentTransfer->toArray())
            ->build();

        $quoteTransfer = $this->removeItemLevelShipments($quoteTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $isValid = $shipmentCheckoutConnectorFacade->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenNonActiveUsedShouldAddErrorWithItemLevelShipment()
    {
        $shipmentCheckoutConnectorFacade = $this->createShipmentCheckoutConnectorFacade();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod()
            ->build();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $isValid = $shipmentCheckoutConnectorFacade->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenNoShipmentGivenShouldPass()
    {
        $shipmentCheckoutConnectorFacade = $this->createShipmentCheckoutConnectorFacade();

        $quoteTransfer = (new QuoteBuilder())
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $isValid = $shipmentCheckoutConnectorFacade->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenValidShipmentGivenShouldPass()
    {
        $shipmentCheckoutConnectorFacade = $this->createShipmentCheckoutConnectorFacade();

        $priceList = [
            $this->getDefaultStoreName() => [
                'EUR' => [
                    'netAmount' => 10,
                    'grossAmount' => 15,
                ],
            ],
        ];

        $idShipmentMethod = $this->tester
            ->haveShipmentMethod([], [], $priceList)
            ->getIdShipmentMethod();

        $shipmentMethodTransfer = (new ShipmentMethodBuilder(
            [
                ShipmentMethodTransfer::ID_SHIPMENT_METHOD => $idShipmentMethod,
            ]
        ))
            ->build();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod($shipmentMethodTransfer->toArray())
            ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentTransfer->toArray())
            ->build();

        $quoteTransfer = $this->removeItemLevelShipments($quoteTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $isValid = $shipmentCheckoutConnectorFacade->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckShipmentWhenValidShipmentGivenShouldPassWithItemLevelShipment()
    {
        $shipmentCheckoutConnectorFacade = $this->createShipmentCheckoutConnectorFacade();

        $priceList = [
            $this->getDefaultStoreName() => [
                'EUR' => [
                    'netAmount' => 10,
                    'grossAmount' => 15,
                ],
            ],
        ];

        $idShipmentMethod = $this->tester
            ->haveShipmentMethod([], [], $priceList)
            ->getIdShipmentMethod();

        $shipmentMethodTransfer = (new ShipmentMethodBuilder(
            [
                ShipmentMethodTransfer::ID_SHIPMENT_METHOD => $idShipmentMethod,
            ]
        ))
            ->build();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod($shipmentMethodTransfer->toArray())
            ->build();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer);

        $quoteTransfer = $this->removeItemLevelShipments($quoteTransfer);

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();

        $isValid = $shipmentCheckoutConnectorFacade->checkShipment($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($isValid);
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorFacadeInterface
     */
    protected function createShipmentCheckoutConnectorFacade()
    {
        return new ShipmentCheckoutConnectorFacade();
    }

    /**
     * @return string
     */
    public function getDefaultStoreName()
    {
        return $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeItemLevelShipments(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment(null);
        }

        return $quoteTransfer;
    }
}
