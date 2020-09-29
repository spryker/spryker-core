<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderMerchantUserGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
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
    public function testIsMerchantOrderShipmentReturnsTrueWithCorrectItemShipment(): void
    {
        //Arrange
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment(1);

        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem(
            (new MerchantOrderItemTransfer())->setOrderItem(
                (new ItemTransfer())->setShipment($shipmentTransfer)
            )
        );

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransfer);

        // Assert
        $this->assertTrue($isMerchantOrderShipment);
    }

    /**
     * @return void
     */
    public function testIsMerchantOrderShipmentReturnsFalseWithWrongItemShipment(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment(1);
        $shipmentTransferFake = (new ShipmentTransfer())->setIdSalesShipment(2);

        $merchantOrderTransfer = (new MerchantOrderTransfer())->addMerchantOrderItem(
            (new MerchantOrderItemTransfer())->setOrderItem(
                (new ItemTransfer())->setShipment($shipmentTransfer)
            )
        );

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransferFake);

        // Assert
        $this->assertFalse($isMerchantOrderShipment);
    }
}
