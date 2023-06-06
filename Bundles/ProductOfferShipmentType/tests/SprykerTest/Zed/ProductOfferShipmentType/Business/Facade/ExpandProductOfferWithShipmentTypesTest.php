<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentType
 * @group Business
 * @group Facade
 * @group ExpandProductOfferWithShipmentTypesTest
 * Add your own group annotations below this line
 */
class ExpandProductOfferWithShipmentTypesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester
     */
    protected ProductOfferShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getProductOfferShipmentTypeQuery());
    }

    /**
     * @return void
     */
    public function testExpandsProductOfferWhenRelatedShipmentTypesExist(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        // Act
        $productOfferTransfer = $this->tester->getFacade()->expandProductOfferWithShipmentTypes($productOfferTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer->getShipmentTypes());
        $this->assertEquals($shipmentTypeTransfer, $productOfferTransfer->getShipmentTypes()->offsetGet(0));
    }

    /**
     * @return void
     */
    public function testDoesntExpandsProductOfferWhenNoRelatedShipmentTypeExists(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferTransferToExpand = $this->tester->haveProductOffer();

        // Act
        $expandedProductOfferTransfer = $this->tester->getFacade()->expandProductOfferWithShipmentTypes($productOfferTransferToExpand);

        // Assert
        $this->assertEmpty($expandedProductOfferTransfer->getShipmentTypes());
    }
}
