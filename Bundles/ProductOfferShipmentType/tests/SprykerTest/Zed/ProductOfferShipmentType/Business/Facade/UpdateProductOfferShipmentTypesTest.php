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
 * @group UpdateProductOfferShipmentTypesTest
 * Add your own group annotations below this line
 */
class UpdateProductOfferShipmentTypesTest extends Unit
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
    public function testCreatesProductOfferShipmentTypesWhenProductOfferHasShipmentTypesWhichNotInPersistence(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $persistedShipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $persistedShipmentTypeTransfer);

        $newShipmentType1Transfer = $this->tester->haveShipmentType();
        $newShipmentType2Transfer = $this->tester->haveShipmentType();
        $productOfferTransfer
            ->addShipmentType($persistedShipmentTypeTransfer)
            ->addShipmentType($newShipmentType1Transfer)
            ->addShipmentType($newShipmentType2Transfer);

        // Act
        $productOfferTransfer = $this->tester->getFacade()->updateProductOfferShipmentTypes($productOfferTransfer);

        // Assert
        $productOfferShipmentTypeEntities = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        $this->assertCount(3, $productOfferShipmentTypeEntities);
        $shipmentTypeIds = array_unique(
            [
                $persistedShipmentTypeTransfer->getIdShipmentTypeOrFail(),
                $newShipmentType1Transfer->getIdShipmentTypeOrFail(),
                $newShipmentType2Transfer->getIdShipmentTypeOrFail(),
            ],
        );
        foreach ($productOfferShipmentTypeEntities as $productOfferShipmentTypeEntity) {
            $this->assertContains($productOfferShipmentTypeEntity->getFkShipmentType(), $shipmentTypeIds);
        }
    }

    /**
     * @return void
     */
    public function testDeletesProductOfferShipmentTypesWhenThereAreMoreProductOfferShipmentTypesInPersistenceThenProductOfferHas(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();

        $persistedShipmentType1Transfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $persistedShipmentType1Transfer);

        $persistedShipmentType2Transfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $persistedShipmentType2Transfer);

        $persistedShipmentType3Transfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $persistedShipmentType3Transfer);

        $productOfferTransfer->addShipmentType($persistedShipmentType1Transfer);

        // Act
        $productOfferTransfer = $this->tester->getFacade()->updateProductOfferShipmentTypes($productOfferTransfer);

        // Assert
        $productOfferShipmentTypeEntities = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        $this->assertCount(1, $productOfferShipmentTypeEntities);
        $productOfferShipmentTypeEntity = $productOfferShipmentTypeEntities->offsetGet(0);
        $this->assertSame(
            $persistedShipmentType1Transfer->getIdShipmentTypeOrFail(),
            $productOfferShipmentTypeEntity->getFkShipmentType(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenProductOfferShipmentTypesMatchPersistedProductOfferShipmentTypes(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();

        $shipmentType1Transfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentType1Transfer);

        $shipmentType2Transfer = $this->tester->haveShipmentType();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentType2Transfer);

        $productOfferTransfer
            ->addShipmentType($shipmentType1Transfer)
            ->addShipmentType($shipmentType2Transfer);

        $productOfferShipmentTypeEntitiesBeforePluginExecution = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );

        // Act
        $productOfferTransfer = $this->tester->getFacade()->updateProductOfferShipmentTypes($productOfferTransfer);

        // Assert
        $productOfferShipmentTypeEntitiesAfterPluginExecution = $this->tester->getProductOfferShipmentTypeEntitiesByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );

        $this->assertEquals(
            $productOfferShipmentTypeEntitiesBeforePluginExecution->getData(),
            $productOfferShipmentTypeEntitiesAfterPluginExecution->getData(),
        );
    }
}
