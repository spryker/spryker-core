<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCartConnector
 * @group Business
 * @group Facade
 * @group ValidateShipmentTest
 * Add your own group annotations below this line
 */
class ValidateShipmentTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_SHIPMENT_METHOD_ID = -1;

    /**
     * @uses \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidator::CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY
     *
     * @var string
     */
    protected const CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY = 'cart.pre.check.shipment.failed';

    /**
     * @var string
     */
    protected const MESSAGE_PARAMETER_METHOD_NAME = '%method_name%';

    /**
     * @var string
     */
    protected const MESSAGE_PARAMETER_CARRIER_NAME = '%carrier_name%';

    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected ShipmentCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenSelectedShipmentHaveNoPrice(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->tester->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->getQuote()->getCurrency()->setCode('LTL');

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateShipment($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenSelectedShipmentHaveNoPriceWithItemLevelShipments(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->getQuote()->getCurrency()->setCode('LTL');

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateShipment($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testShouldReturnCorrectValidationErrorMessage(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = (new ShipmentMethodBuilder([ShipmentMethodTransfer::ID_SHIPMENT_METHOD => static::FAKE_SHIPMENT_METHOD_ID]))->build();

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateShipment($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());

        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        $messageTransfer = $cartPreCheckResponseTransfer->getMessages()->getIterator()->current();
        $this->assertSame(static::CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY, $messageTransfer->getValue());
        $this->assertCount(2, $messageTransfer->getParameters());
        $this->assertSame([
            static::MESSAGE_PARAMETER_METHOD_NAME => $shipmentMethodTransfer->getName(),
            static::MESSAGE_PARAMETER_CARRIER_NAME => $shipmentMethodTransfer->getCarrierName(),
        ], $messageTransfer->getParameters());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenSelectedShipmentHavePrice(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);

        // Act
        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenSelectedShipmentHavePriceWithItemLevelShipments(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }
}
