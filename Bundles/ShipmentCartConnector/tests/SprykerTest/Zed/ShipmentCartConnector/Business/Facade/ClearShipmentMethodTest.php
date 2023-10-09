<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business\Facade;

use Codeception\Test\Unit;
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
 * @group ClearShipmentMethodTest
 * Add your own group annotations below this line
 */
class ClearShipmentMethodTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_ADD
     *
     * @var string
     */
    protected const OPERATION_ADD = 'add';

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     *
     * @var string
     */
    protected const OPERATION_REMOVE = 'remove';

    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected ShipmentCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldClearItemLevelShipmentOnAddOperation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();
        $this->assertEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testShouldClearItemLevelShipmentOnRemoveOperation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_REMOVE);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();
        $this->assertEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testShouldClearShipmentExpenses(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $this->assertEmpty($cartChangeTransfer->getQuote()->getExpenses());
    }

    /**
     * @return void
     */
    public function testShouldNotClearItemLevelShipment(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();
        $this->assertNotEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testShouldClearQuoteLevelShipment(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithQuoteLevelShipment($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $this->assertEmpty($cartChangeTransfer->getQuote()->getShipment()->getMethod());
    }
}
