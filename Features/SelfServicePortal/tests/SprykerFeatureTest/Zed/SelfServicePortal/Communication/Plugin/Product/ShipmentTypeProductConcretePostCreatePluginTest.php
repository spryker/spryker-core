<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ShipmentTypeProductConcretePostCreatePlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ShipmentTypeProductConcretePostCreatePluginTest
 */
class ShipmentTypeProductConcretePostCreatePluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function _before(): void
    {
        $this->tester->ensureProductShipmentTypeTableIsEmpty();
    }

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
