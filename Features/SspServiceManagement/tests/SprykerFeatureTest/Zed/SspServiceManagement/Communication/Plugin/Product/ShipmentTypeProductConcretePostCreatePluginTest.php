<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Product;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Product\ShipmentTypeProductConcretePostCreatePlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ShipmentTypeProductConcretePostCreatePluginTest
 */
class ShipmentTypeProductConcretePostCreatePluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->ensureProductShipmentTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testCreateShouldCreateProductShipmentTypeRelation(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer->addShipmentType($shipmentTypeTransfer);

        // Act
        $shipmentTypeProductConcretePostCreatePlugin = new ShipmentTypeProductConcretePostCreatePlugin();
        $shipmentTypeProductConcretePostCreatePlugin->create($productConcreteTransfer);

        $productShipmentTypeRelationExists = $this->tester->ensureProductShipmentTypeRelationExists(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        // Assert
        $this->assertTrue($productShipmentTypeRelationExists);
    }

    /**
     * @return void
     */
    public function testCreateShouldNotCreateProductShipmentTypeRelationWhenProductHasNoShipmentTypes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        // Act
        $shipmentTypeProductConcretePostCreatePlugin = new ShipmentTypeProductConcretePostCreatePlugin();
        $shipmentTypeProductConcretePostCreatePlugin->create($productConcreteTransfer);

        $productShipmentTypeRelationExists = $this->tester->ensureProductShipmentTypeRelationExists(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        // Assert
        $this->assertFalse($productShipmentTypeRelationExists);
    }
}
