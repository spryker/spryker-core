<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi\MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;
use SprykerTest\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypeServicePointsRestApi
 * @group Plugin
 * @group ShipmentsRestApi
 * @group MultiShipmentTypeServicePointShippingAddressValidationStrategyPluginTest
 * Add your own group annotations below this line
 */
class MultiShipmentTypeServicePointShippingAddressValidationStrategyPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiTester
     */
    protected ShipmentTypeServicePointsRestApiTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueWhenApplicableShipmentMethodGivenInMultiShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethod();

        // Act
        $isApplicable = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueWhenApplicableAndNonApplicableShipmentMethodsGivenInMultiShipmentRequest(): void
    {
        // Arrange
        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableAndNonApplicableShipmentMethods();

        // Act
        $isApplicable = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenNonApplicableShipmentMethodGivenInMultiShipmentRequest(): void
    {
        // Arrange
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$nonApplicableShipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithNonApplicableShipmentMethod();

        // Act
        $isApplicable = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenApplicableShipmentMethodGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethod();

        // Act
        $isApplicable = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenNonApplicableShipmentMethodGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithNonApplicableShipmentMethod();

        // Act
        $isApplicable = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testValidateReturnsErrorWhenShippingAddressNotProvidedForNonApplicableItems(): void
    {
        // Arrange
        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient(
            [
                $applicableShipmentTypeStorageTransfer,
                $nonApplicableShipmentTypeStorageTransfer,
            ],
        );

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableAndNonApplicableShipmentMethodsWithoutAddress();

        // Act
        $restErrorCollectionTransfer = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->validate($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertSame(
            sprintf(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ITEM_SHIPPING_ADDRESS_MISSING,
                ShipmentTypeServicePointsRestApiTester::ITEM_GROUP_KEY_1,
            ),
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertSame(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ITEM_SHIPPING_ADDRESS_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
    }

    /**
     * @return void
     */
    public function testValidateReturnsNoErrorWhenShippingAddressProvidedForNonApplicableItems(): void
    {
        // Arrange
        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient(
            [
                $applicableShipmentTypeStorageTransfer,
                $nonApplicableShipmentTypeStorageTransfer,
            ],
        );

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableAndNonApplicableShipmentMethods();

        // Act
        $restErrorCollectionTransfer = (new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->validate($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }
}
