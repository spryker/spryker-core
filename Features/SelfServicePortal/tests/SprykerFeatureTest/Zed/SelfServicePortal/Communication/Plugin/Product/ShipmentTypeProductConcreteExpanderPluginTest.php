<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ShipmentTypeProductConcreteExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ShipmentTypeProductConcreteExpanderPluginTest
 */
class ShipmentTypeProductConcreteExpanderPluginTest extends Unit
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
    public function testExpandShouldExpandProductConcreteWithExistingShipmentTypes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        // Act
        $expandedProductConcreteTransfers = (new ShipmentTypeProductConcreteExpanderPlugin())
            ->expand([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteTransfers);
        $this->assertCount(1, $expandedProductConcreteTransfers[0]->getShipmentTypes());
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $expandedProductConcreteTransfers[0]->getShipmentTypes()[0]->getIdShipmentTypeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldNotExpandProductConcreteWithoutShipmentTypes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        // Act
        $expandedProductConcreteTransfers = (new ShipmentTypeProductConcreteExpanderPlugin())
            ->expand([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteTransfers);
        $this->assertCount(0, $expandedProductConcreteTransfers[0]->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandShouldExpandProductConcreteOnlyWithRelatedShipmentTypes(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $firstShipmentTypeTransfer = $this->tester->haveShipmentType();
        $secondShipmentTypeTransfer = $this->tester->haveShipmentType();

        $firstProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $firstProductConcreteTransfer,
            [$firstShipmentTypeTransfer],
        );
        $secondProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $secondProductConcreteTransfer,
            [$secondShipmentTypeTransfer],
        );

        // Act
        $expandedProductConcreteTransfers = (new ShipmentTypeProductConcreteExpanderPlugin())
            ->expand([$firstProductConcreteTransfer]);

        // Assert
        $this->assertCount(1, $expandedProductConcreteTransfers);
        $this->assertCount(1, $expandedProductConcreteTransfers[0]->getShipmentTypes());
        $this->assertSame(
            $firstShipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $expandedProductConcreteTransfers[0]->getShipmentTypes()[0]->getIdShipmentTypeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldExpandMultipleProductsWithTheirShipmentTypes(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $firstProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $firstProductConcreteTransfer,
            [$shipmentTypeTransfer],
        );
        $secondProductConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $secondProductConcreteTransfer,
            [$shipmentTypeTransfer],
        );

        // Act
        $expandedProductConcreteTransfers = (new ShipmentTypeProductConcreteExpanderPlugin())
            ->expand([$firstProductConcreteTransfer, $secondProductConcreteTransfer]);

        // Assert
        $this->assertCount(2, $expandedProductConcreteTransfers);
        $this->assertCount(1, $expandedProductConcreteTransfers[0]->getShipmentTypes());
        $this->assertCount(1, $expandedProductConcreteTransfers[1]->getShipmentTypes());
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $expandedProductConcreteTransfers[0]->getShipmentTypes()[0]->getIdShipmentTypeOrFail(),
        );
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $expandedProductConcreteTransfers[1]->getShipmentTypes()[0]->getIdShipmentTypeOrFail(),
        );
    }
}
