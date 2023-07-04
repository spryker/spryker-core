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
 * @group CreateProductOfferShipmentTypesTest
 * Add your own group annotations below this line
 */
class CreateProductOfferShipmentTypesTest extends Unit
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
    public function testCreatesShipmentTypesWhenProductOfferHasNotEmptyShipmentTypeCollection(): void
    {
        // Arrange
        $shipmentType1Transfer = $this->tester->haveShipmentType();
        $shipmentType2Transfer = $this->tester->haveShipmentType();
        $productOfferTransfer = $this->tester->haveProductOffer()
            ->addShipmentType($shipmentType1Transfer)
            ->addShipmentType($shipmentType2Transfer);

        // Act
        $productOfferTransfer = $this->tester->getFacade()->createProductOfferShipmentTypes($productOfferTransfer);

        // Assert
        $productOfferShipmentTypeEntities = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        $this->assertNotEmpty($productOfferShipmentTypeEntities);
        $this->assertCount($productOfferTransfer->getShipmentTypes()->count(), $productOfferShipmentTypeEntities);
        $shipmentTypeIds = array_unique(
            [
                $shipmentType1Transfer->getIdShipmentTypeOrFail(),
                $shipmentType2Transfer->getIdShipmentTypeOrFail(),
            ],
        );
        foreach ($productOfferShipmentTypeEntities as $productOfferShipmentTypeEntity) {
            $this->assertContains($productOfferShipmentTypeEntity->getFkShipmentType(), $shipmentTypeIds);
        }
    }

    /**
     * @return void
     */
    public function testDoesntCreateShipmentTypesWhenProductOfferHasEmptyShipmentTypeCollection(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();

        // Act
        $productOfferTransfer = $this->tester->getFacade()->createProductOfferShipmentTypes($productOfferTransfer);

        // Assert
        $productOfferShipmentTypeEntities = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        $this->assertEmpty($productOfferShipmentTypeEntities);
    }
}
