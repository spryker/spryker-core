<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group FindMethodByIdTest
 * Add your own group annotations below this line
 */
class FindMethodByIdTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindMethodByIdShouldFindShipmentMethod(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        // Act
        $resultTransfer = $this->tester->getFacade()->findMethodById($shipmentMethodTransfer->getIdShipmentMethod());

        // Assert
        $this->assertNotNull($resultTransfer, 'Result should not be null');
    }

    /**
     * @return void
     */
    public function testFindMethodByIdShouldNotReturnShipmentNethod(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();

        // Act
        $shipmentMethodTransfer = $this->tester->getFacade()->findMethodById(100);

        // Assert
        $this->assertNull($shipmentMethodTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesStackOfShipmentMethodCollectionExpanderPlugins(): void
    {
        // Arrange
        $this->tester->setDependency(
            ShipmentDependencyProvider::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER,
            [$this->getShipmentMethodCollectionExpanderPluginMock()],
        );
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([ShipmentMethodTransfer::IS_ACTIVE => true]);

        // Act
        $this->tester->getFacade()->findMethodById($shipmentMethodTransfer->getIdShipmentMethodOrFail());
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface
     */
    protected function getShipmentMethodCollectionExpanderPluginMock(): ShipmentMethodCollectionExpanderPluginInterface
    {
        $shipmentMethodCollectionExpanderPluginMock = $this
            ->getMockBuilder(ShipmentMethodCollectionExpanderPluginInterface::class)
            ->getMock();

        $shipmentMethodCollectionExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function (ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer) {
                return $shipmentMethodCollectionTransfer;
            });

        return $shipmentMethodCollectionExpanderPluginMock;
    }
}
