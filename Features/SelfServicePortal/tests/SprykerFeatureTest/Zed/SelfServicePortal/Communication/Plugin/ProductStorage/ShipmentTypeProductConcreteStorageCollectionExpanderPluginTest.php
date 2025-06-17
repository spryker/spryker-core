<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductStorage\ShipmentTypeProductConcreteStorageCollectionExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductStorage
 * @group ShipmentTypeProductConcreteStorageCollectionExpanderPluginTest
 */
class ShipmentTypeProductConcreteStorageCollectionExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

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
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail());

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
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail());

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
        $firstProductConcreteTransfer = $this->tester->haveFullProduct();
        $secondProductConcreteTransfer = $this->tester->haveFullProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $firstProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $firstProductConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        $firstProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($firstProductConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($firstProductConcreteTransfer->getFkProductAbstractOrFail());

        $secondProductConcreteStorageTransfer = (new ProductConcreteStorageTransfer())
            ->setIdProductConcrete($secondProductConcreteTransfer->getIdProductConcreteOrFail())
            ->setIdProductAbstract($secondProductConcreteTransfer->getFkProductAbstractOrFail());

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
