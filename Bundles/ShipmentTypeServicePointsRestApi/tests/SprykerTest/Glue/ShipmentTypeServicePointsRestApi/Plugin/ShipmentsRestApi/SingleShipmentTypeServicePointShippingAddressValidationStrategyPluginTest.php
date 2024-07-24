<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi\SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin;
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
 * @group SingleShipmentTypeServicePointShippingAddressValidationStrategyPluginTest
 * Add your own group annotations below this line
 */
class SingleShipmentTypeServicePointShippingAddressValidationStrategyPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiTester
     */
    protected ShipmentTypeServicePointsRestApiTester $tester;

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueWhenApplicableShipmentMethodGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);
        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethod();

        // Act
        $isApplicable = (new SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenNonApplicableShipmentMethodGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);
        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithNonApplicableShipmentMethod();

        // Act
        $isApplicable = (new SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->isApplicable($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testValidateReturnsBadRequestWhenNoServicePointGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);
        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithoutServicePoints();

        // Act
        $restErrorCollectionTransfer = (new SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->validate($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertSame(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
    }

    /**
     * @return void
     */
    public function testValidateReturnsNoErrorsWhenServicePointGivenInSingleShipmentRequest(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);
        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin())
            ->setFactory($this->tester->getFactory())
            ->validate($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }
}
