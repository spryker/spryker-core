<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ShipmentTypeProductConcreteForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ShipmentTypeProductConcreteFormEditDataProviderExpanderPluginTest
 */
class ShipmentTypeProductConcreteFormEditDataProviderExpanderPluginTest extends Unit
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
    public function testExpandShouldMapShipmentTypesToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );
        $formData = [];

        // Act
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin = new ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin();
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES, $formData);
        $this->assertNotEmpty($formData[ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES]);
        $this->assertInstanceOf(ShipmentTypeTransfer::class, $formData[ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES][0]);
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $formData[ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES][0]->getIdShipmentTypeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldNotMapShipmentTypesWhenProductHasNoShipmentTypes(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $formData = [];

        // Act
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin = new ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin();
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertEmpty($formData);
    }

    /**
     * @return void
     */
    public function testExpandShouldPreserveExistingFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$shipmentTypeTransfer],
        );
        $formData = ['existingKey' => 'existingValue'];

        // Act
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin = new ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin();
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey('existingKey', $formData);
        $this->assertSame('existingValue', $formData['existingKey']);
        $this->assertArrayHasKey(ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES, $formData);
    }

    /**
     * @return void
     */
    public function testExpandShouldMapMultipleShipmentTypesToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $firstShipmentTypeTransfer = $this->tester->haveShipmentType();
        $secondShipmentTypeTransfer = $this->tester->haveShipmentType();
        $productConcreteTransfer = $this->tester->addShipmentTypesToProduct(
            $productConcreteTransfer,
            [$firstShipmentTypeTransfer, $secondShipmentTypeTransfer],
        );
        $formData = [];

        // Act
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin = new ShipmentTypeProductConcreteFormEditDataProviderExpanderPlugin();
        $shipmentTypeProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES, $formData);
        $this->assertCount(2, $formData[ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES]);
        $this->assertContainsOnlyInstancesOf(ShipmentTypeTransfer::class, $formData[ShipmentTypeProductConcreteForm::FIELD_SHIPMENT_TYPES]);
    }
}
