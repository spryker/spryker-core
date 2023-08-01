<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

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
 * @group Facade
 * @group GetMethodsTest
 * Add your own group annotations below this line
 */
class GetMethodsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

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
        $this->tester->haveShipmentMethod([ShipmentMethodTransfer::IS_ACTIVE => true]);

        // Act
        $this->tester->getFacade()->getMethods();
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
