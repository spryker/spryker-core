<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi\ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;
use SprykerTest\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypeServicePointsRestApi
 * @group Plugin
 * @group CheckoutRestApi
 * @group ShipmentTypeServicePointCheckoutRequestAttributesValidatorPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypeServicePointCheckoutRequestAttributesValidatorPluginTest extends Unit
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
    public function testReturnsErrorWhenServicePointIsNotProvidedForApplicableShipmentTypeAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithoutServicePoints();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenServicePointIsProvidedForNotApplicableShipmentTypeAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $this->tester->mockShipmentTypeStorageClient([]);

        $restCheckoutRequestAttributesTransfer = $this->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            sprintf(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_FOR_ITEM_SHOULD_NOT_BE_PROVIDED,
                ShipmentTypeServicePointsRestApiTester::ITEM_GROUP_KEY_1,
            ),
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_FOR_ITEM_SHOULD_NOT_BE_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenServicePointHasNoAddressAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransfer();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            sprintf(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_ADDRESS_MISSING,
                ShipmentTypeServicePointsRestApiTester::SERVICE_POINT_UUID_1,
            ),
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_ADDRESS_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerFirstNameIsMissedAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerFirstName();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerLastNameIsMissedAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerLastName();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerSalutationIsMissedAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerSalutation();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenValidAttributesAndMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenServicePointIsNotProvidedForApplicableShipmentTypeAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithoutServicePoints();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenServicePointIsProvidedForNotApplicableShipmentTypeAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $this->tester->mockShipmentTypeStorageClient([]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_SHOULD_NOT_BE_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_SHOULD_NOT_BE_PROVIDED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenMultipleServicePointsAreProvidedForApplicableShipmentTypeAndSingleShipmentRequestGiven(): void
    {
        // Arrange

        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithMultipleServicePoints();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenServicePointHasNoAddressAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransfer();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            sprintf(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_ADDRESS_MISSING,
                ShipmentTypeServicePointsRestApiTester::SERVICE_POINT_UUID_1,
            ),
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_ADDRESS_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerFirstNameIsMissedAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerFirstName();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerLastNameIsMissedAndSingleShipmentRequestGiven(): void
    {
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerLastName();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCustomerSalutationIsMissedAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerSalutation();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getDetail(),
        );
        $this->assertEquals(
            ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getCode(),
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenValidAttributesAndSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $this->tester->mockShipmentTypeStorageClient([$shipmentTypeStorageTransfer]);

        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this
            ->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        // Act
        $restErrorCollectionTransfer = (new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($this->tester->getFactory())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertEmpty($restErrorCollectionTransfer->getRestErrors());
    }
}
