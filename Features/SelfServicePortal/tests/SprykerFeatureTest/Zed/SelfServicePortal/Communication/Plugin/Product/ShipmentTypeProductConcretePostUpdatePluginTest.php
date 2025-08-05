<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use ArrayObject;
use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ShipmentTypeProductConcretePostUpdatePlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ShipmentTypeProductConcretePostUpdatePluginTest
 */
class ShipmentTypeProductConcretePostUpdatePluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function _before(): void
    {
        $this->tester->ensureProductShipmentTypeTableIsEmpty();
    }

    public function testUpdateAddsNewShipmentTypeToExistingOnes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $existingShipmentTypeTransfer = $this->tester->haveShipmentType();
        $newShipmentTypeTransfer = $this->tester->haveShipmentType();

        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$existingShipmentTypeTransfer],
        );

        $productConcreteTransfer->addShipmentType($newShipmentTypeTransfer);

        // Act
        $shipmentTypeProductConcretePostUpdatePlugin = new ShipmentTypeProductConcretePostUpdatePlugin();
        $shipmentTypeProductConcretePostUpdatePlugin->update($productConcreteTransfer);

        // Assert
        $shipmentTypeIds = $this->tester->getProductShipmentTypeIds($productConcreteTransfer->getIdProductConcreteOrFail());
        $this->assertCount(2, $shipmentTypeIds);
        $this->assertContains($existingShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
        $this->assertContains($newShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
    }

    public function testUpdateReplacesExistingShipmentTypesWithNewOnes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $oldShipmentTypeTransfer = $this->tester->haveShipmentType();
        $newShipmentTypeTransfer = $this->tester->haveShipmentType();

        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$oldShipmentTypeTransfer],
        );

        $productConcreteTransfer->setShipmentTypes(new ArrayObject([$newShipmentTypeTransfer]));

        // Act
        $shipmentTypeProductConcretePostUpdatePlugin = new ShipmentTypeProductConcretePostUpdatePlugin();
        $shipmentTypeProductConcretePostUpdatePlugin->update($productConcreteTransfer);

        // Assert
        $shipmentTypeIds = $this->tester->getProductShipmentTypeIds($productConcreteTransfer->getIdProductConcreteOrFail());
        $this->assertCount(1, $shipmentTypeIds);
        $this->assertContains($newShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
        $this->assertNotContains($oldShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
    }

    public function testUpdateWithSameShipmentTypesDoesNotChangeRelations(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        // Act
        $shipmentTypeProductConcretePostUpdatePlugin = new ShipmentTypeProductConcretePostUpdatePlugin();
        $shipmentTypeProductConcretePostUpdatePlugin->update($productConcreteTransfer);

        // Assert
        $shipmentTypeIds = $this->tester->getProductShipmentTypeIds($productConcreteTransfer->getIdProductConcreteOrFail());
        $this->assertCount(1, $shipmentTypeIds);
        $this->assertContains($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
    }

    public function testUpdateWithEmptyShipmentTypesDeletesAllRelations(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        $productConcreteTransfer->setShipmentTypes(new ArrayObject([]));

        // Act
        $shipmentTypeProductConcretePostUpdatePlugin = new ShipmentTypeProductConcretePostUpdatePlugin();
        $shipmentTypeProductConcretePostUpdatePlugin->update($productConcreteTransfer);

        // Assert
        $shipmentTypeIds = $this->tester->getProductShipmentTypeIds($productConcreteTransfer->getIdProductConcreteOrFail());
        $this->assertEmpty($shipmentTypeIds);
    }

    public function testUpdateWithMultipleShipmentTypesCreatesAllRelations(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $firstShipmentTypeTransfer = $this->tester->haveShipmentType();
        $secondShipmentTypeTransfer = $this->tester->haveShipmentType();
        $thirdShipmentTypeTransfer = $this->tester->haveShipmentType();

        $productConcreteTransfer->setShipmentTypes(new ArrayObject([
            $firstShipmentTypeTransfer,
            $secondShipmentTypeTransfer,
            $thirdShipmentTypeTransfer,
        ]));

        // Act
        $shipmentTypeProductConcretePostUpdatePlugin = new ShipmentTypeProductConcretePostUpdatePlugin();
        $shipmentTypeProductConcretePostUpdatePlugin->update($productConcreteTransfer);

        // Assert
        $shipmentTypeIds = $this->tester->getProductShipmentTypeIds($productConcreteTransfer->getIdProductConcreteOrFail());
        $this->assertCount(3, $shipmentTypeIds);
        $this->assertContains($firstShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
        $this->assertContains($secondShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
        $this->assertContains($thirdShipmentTypeTransfer->getIdShipmentTypeOrFail(), $shipmentTypeIds);
    }
}
