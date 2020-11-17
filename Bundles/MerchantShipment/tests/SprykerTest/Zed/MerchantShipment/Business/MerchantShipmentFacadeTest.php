<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantShipment
 * @group Business
 * @group MerchantShipmentFacadeTest
 * Add your own group annotations below this line
 */
class MerchantShipmentFacadeTest extends Unit
{
    protected const TEST_MERCHANT_REFERENCE = 'test_merchant_reference';
    protected const TEST_WRONG_MERCHANT_REFERENCE = 'test_wrong_merchant_reference';

    /**
     * @var \SprykerTest\Zed\MerchantShipment\MerchantShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindShipmentByIdShipmentReturnsShipmentTransfer(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $shipmentTransfer = $this->tester->haveShipment($orderEntity->getIdSalesOrder());

        $merchantShipmentCriteriaTransfer = (new MerchantShipmentCriteriaTransfer())
            ->setIdShipment($shipmentTransfer->getIdSalesShipment());

        // Act
        $shipmentTransfer = $this->tester->getFacade()->findShipment($merchantShipmentCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(ShipmentTransfer::class, $shipmentTransfer);
        $this->assertEquals($merchantShipmentCriteriaTransfer->getIdShipment(), $shipmentTransfer->getIdSalesShipment());
    }

    /**
     * @return void
     */
    public function testFindShipmentByIdShipmentReturnsNull(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $shipmentTransfer = $this->tester->haveShipment($orderEntity->getIdSalesOrder());

        $merchantShipmentCriteriaTransfer = (new MerchantShipmentCriteriaTransfer())
            ->setIdShipment($shipmentTransfer->getIdSalesShipment() + 1);

        // Act
        $shipmentTransfer = $this->tester->getFacade()->findShipment($merchantShipmentCriteriaTransfer);

        // Assert
        $this->assertNull($shipmentTransfer);
    }

    /**
     * @return void
     */
    public function testFindShipmentByMerchantReferenceReturnsShipmentTransfer(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $shipmentTransfer = $this->tester->haveShipment($orderEntity->getIdSalesOrder(), [
            ShipmentTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ]);

        $merchantShipmentCriteriaTransfer = (new MerchantShipmentCriteriaTransfer())
            ->setMerchantReference(static::TEST_MERCHANT_REFERENCE);

        // Act
        $shipmentTransfer = $this->tester->getFacade()->findShipment($merchantShipmentCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(ShipmentTransfer::class, $shipmentTransfer);
        $this->assertEquals($merchantShipmentCriteriaTransfer->getMerchantReference(), $shipmentTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testFindShipmentByMerchantReferenceReturnsNull(): void
    {
        // Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $shipmentTransfer = $this->tester->haveShipment($orderEntity->getIdSalesOrder(), [
            ShipmentTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ]);

        $merchantShipmentCriteriaTransfer = (new MerchantShipmentCriteriaTransfer())
            ->setMerchantReference(static::TEST_WRONG_MERCHANT_REFERENCE);

        // Act
        $shipmentTransfer = $this->tester->getFacade()->findShipment($merchantShipmentCriteriaTransfer);

        // Assert
        $this->assertNull($shipmentTransfer);
    }
}
