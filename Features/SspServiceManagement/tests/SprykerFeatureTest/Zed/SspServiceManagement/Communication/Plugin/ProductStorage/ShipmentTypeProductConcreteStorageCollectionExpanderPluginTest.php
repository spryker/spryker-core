<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\ProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductStorage\ShipmentTypeProductConcreteStorageCollectionExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group ProductStorage
 * @group ShipmentTypeProductConcreteStorageCollectionExpanderPluginTest
 */
class ShipmentTypeProductConcreteStorageCollectionExpanderPluginTest extends Unit
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
    public function testExpandShouldExpandProductConcreteWithShipmentTypeUuids(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail());

        // Act
        $expandedProductConcreteStorageTransfers = (new ShipmentTypeProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$productConcreteStorageTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteStorageTransfers);
        $this->assertCount(1, $expandedProductConcreteStorageTransfers[0]->getShipmentTypeUuids());
        $this->assertSame(
            $shipmentTypeTransfer->getUuidOrFail(),
            $expandedProductConcreteStorageTransfers[0]->getShipmentTypeUuids()[0],
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldNotExpandProductConcreteWithoutShipmentTypes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail());

        // Act
        $expandedProductConcreteStorageTransfers = (new ShipmentTypeProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$productConcreteStorageTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteStorageTransfers);
        $this->assertEmpty($expandedProductConcreteStorageTransfers[0]->getShipmentTypeUuids());
    }

    /**
     * @return void
     */
    public function testExpandShouldExpandOnlyWithRelatedShipmentTypes(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $firstProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $firstProductConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        $firstProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($firstProductConcreteTransfer->getIdProductConcreteOrFail());
        $secondProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($secondProductConcreteTransfer->getIdProductConcreteOrFail());

        // Act
        $expandedProductConcreteStorageTransfers = (new ShipmentTypeProductConcreteStorageCollectionExpanderPlugin())
            ->expand([$firstProductConcreteStorageTransfer, $secondProductConcreteStorageTransfer]);

        // Assert
        $this->assertCount(2, $expandedProductConcreteStorageTransfers);
        $this->assertCount(1, $expandedProductConcreteStorageTransfers[0]->getShipmentTypeUuids());
        $this->assertEmpty($expandedProductConcreteStorageTransfers[1]->getShipmentTypeUuids());
    }

    /**
     * @return void
     */
    public function testExpandShouldHandleEmptyCollection(): void
    {
        // Act
        $expandedProductConcreteStorageTransfers = (new ShipmentTypeProductConcreteStorageCollectionExpanderPlugin())
            ->expand([]);

        // Assert
        $this->assertEmpty($expandedProductConcreteStorageTransfers);
    }
}
