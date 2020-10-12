<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantShipment
 * @group Business
 * @group Facade
 * @group MerchantShipmentFacadeTest
 * Add your own group annotations below this line
 */
class MerchantShipmentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantShipment\MerchantShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsMerchantOrderShipmentReturnsTrueWithCorrectItemShipment(): void
    {
        //Arrange
        $merchantShipmentTransfer = $this->tester->haveMerchantShipment();
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment($merchantShipmentTransfer->getIdSalesShipment());
        $merchantOrderTransfer = (new MerchantOrderTransfer())->setMerchantReference($merchantShipmentTransfer->getMerchantReference());

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment(
            $merchantOrderTransfer->getMerchantReference(),
            $shipmentTransfer
        );

        // Assert
        $this->assertTrue($isMerchantOrderShipment);
    }

    /**
     * @return void
     */
    public function testIsMerchantOrderShipmentReturnsFalseWithWrongItemShipment(): void
    {
        // Arrange
        $merchantShipmentTransfer = $this->tester->haveMerchantShipment();
        $shipmentTransfer = (new ShipmentTransfer())->setIdSalesShipment($merchantShipmentTransfer->getIdSalesShipment() + 1);
        $merchantOrderTransfer = (new MerchantOrderTransfer())->setMerchantReference($merchantShipmentTransfer->getMerchantReference());

        // Act
        $isMerchantOrderShipment = $this->tester->getFacade()->isMerchantOrderShipment(
            $merchantOrderTransfer->getMerchantReference(),
            $shipmentTransfer
        );

        // Assert
        $this->assertFalse($isMerchantOrderShipment);
    }
}
