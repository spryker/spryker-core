<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderMerchantUserGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrderMerchantUserGui
 * @group Business
 * @group Facade
 * @group MerchantSalesOrderMerchantUserGuiFacadeTest
 * Add your own group annotations below this line
 */
class MerchantSalesOrderMerchantUserGuiFacadeTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsMerchantOrderShipmentReturnsTrue(): void
    {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment(1);
        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);

        $saveOrderTransfer = $this->tester->haveOrder([
            ItemTransfer::SHIPMENT => $shipmentTransfer,
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);

        $orderTransfer = (new OrderTransfer())->fromArray($saveOrderTransfer->toArray(), true)
            ->setItems($saveOrderTransfer->getOrderItems());

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $orderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::ORDER => $orderTransfer,
        ]);

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransfer);

        // Assert
        $this->assertTrue($isMerchantOrderShipment);
    }

    /**
     * @return void
     */
    public function testIsMerchantOrderShipmentReturnsFalse(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment(1);
        $shipmentTransferFake = (new ShipmentTransfer())->setIdSalesShipment(2);
        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);

        $saveOrderTransfer = $this->tester->haveOrder([
            ItemTransfer::SHIPMENT => $shipmentTransfer,
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);

        $orderTransfer = (new OrderTransfer())->fromArray($saveOrderTransfer->toArray(), true)
            ->setItems($saveOrderTransfer->getOrderItems());

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $orderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::ORDER => $orderTransfer,
        ]);

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransferFake);

        // Assert
        $this->assertFalse($isMerchantOrderShipment);
    }
}
