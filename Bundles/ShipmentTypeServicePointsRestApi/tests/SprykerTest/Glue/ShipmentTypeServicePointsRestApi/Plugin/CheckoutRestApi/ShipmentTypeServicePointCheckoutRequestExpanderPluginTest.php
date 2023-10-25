<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi\ShipmentTypeServicePointCheckoutRequestExpanderPlugin;
use SprykerTest\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypeServicePointsRestApi
 * @group Plugin
 * @group CheckoutRestApi
 * @group ShipmentTypeServicePointCheckoutRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypeServicePointCheckoutRequestExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_APPLICABLE_SHIPMENT_TYPE_KEY = 'test-pickup';

    /**
     * @var string
     */
    protected const TEST_DELIVERY_SHIPMENT_TYPE_KEY = 'test-delivery';

    /**
     * @var int
     */
    protected const TEST_APPLICABLE_ID_SHIPMENT_METHOD = 7;

    /**
     * @var int
     */
    protected const TEST_DELIVERY_ID_SHIPMENT_METHOD = 1;

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID = 'test-service-point-uuid';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU_1 = 'test-sku-1';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU_2 = 'test-sku-2';

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
    public function testReplacesShippingAddressOfShipmentWithApplicableShipmentMethodForSplitShipment(): void
    {
        // Arrange
        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $servicePointAddressStorageTransfer = $servicePointStorageTransfer->getAddressOrFail();

        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();

        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableAndNonApplicableShipmentMethods();
        $restCustomerTransfer = clone $restCheckoutRequestAttributesTransfer->getCustomerOrFail();
        $nonApplicableShipmentShippingAddressData = $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(1)->getShippingAddressOrFail()->toArray();

        // Act
        $restCheckoutRequestAttributesTransfer = (new ShipmentTypeServicePointCheckoutRequestExpanderPlugin())
            ->setFactory($this->tester->getFactory())
            ->expand($this->getRestRequestMock(), $restCheckoutRequestAttributesTransfer);

        // Assert
        $restShippingAddressTransfer = $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getShippingAddress();
        $this->assertNotNull($restShippingAddressTransfer);
        $this->tester->assertShippingAddressReplaced(
            $restCustomerTransfer,
            $servicePointAddressStorageTransfer,
            $restShippingAddressTransfer,
        );
        $this->assertSame(
            $nonApplicableShipmentShippingAddressData,
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(1)->getShippingAddress()->toArray(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNoShipmentWithApplicableShipmentMethodProvidedForSplitShipment(): void
    {
        // Arrange
        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();

        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this->tester
            ->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod();
        $restShipmentsTransfer = clone $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0);

        // Act
        $restCheckoutRequestAttributesTransfer = (new ShipmentTypeServicePointCheckoutRequestExpanderPlugin())
            ->setFactory($this->tester->getFactory())
            ->expand($this->getRestRequestMock(), $restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertSame(
            $restShipmentsTransfer->getShippingAddressOrFail()->toArray(),
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getShippingAddress()->toArray(),
        );
    }

    /**
     * @return void
     */
    public function testReplacesShippingAddressOfShipmentWithApplicableShipmentMethodForSingleShipment(): void
    {
        // Arrange
        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $servicePointAddressStorageTransfer = $servicePointStorageTransfer->getAddressOrFail();

        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();

        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $restCustomerTransfer = clone $restCheckoutRequestAttributesTransfer->getCustomerOrFail();

        // Act
        $restCheckoutRequestAttributesTransfer = (new ShipmentTypeServicePointCheckoutRequestExpanderPlugin())
            ->setFactory($this->tester->getFactory())
            ->expand(
                $this->getRestRequestMock(),
                $restCheckoutRequestAttributesTransfer,
            );

        // Assert
        $restShippingAddressTransfer = $restCheckoutRequestAttributesTransfer->getShippingAddress();
        $this->assertNotNull($restShippingAddressTransfer);
        $this->tester->assertShippingAddressReplaced(
            $restCustomerTransfer,
            $servicePointAddressStorageTransfer,
            $restShippingAddressTransfer,
        );
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNoShipmentWithApplicableShipmentMethodProvidedForSingleShipment(): void
    {
        // Arrange
        $servicePointStorageTransfer = $this->tester->createServicePointStorageTransferWithAddress();
        $applicableShipmentTypeStorageTransfer = $this->tester->createApplicableShipmentTypeStorageTransfer();
        $nonApplicableShipmentTypeStorageTransfer = $this->tester->createNonApplicableShipmentTypeStorageTransfer();

        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);
        $this->tester->mockServicePointStorageClient([$servicePointStorageTransfer]);

        $restCheckoutRequestAttributesTransfer = $this->tester
            ->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod();
        $originalRestShippingAddressData = $restCheckoutRequestAttributesTransfer->getShippingAddress()->toArray();

        $this->tester->mockShipmentTypeStorageClient([
            $applicableShipmentTypeStorageTransfer,
            $nonApplicableShipmentTypeStorageTransfer,
        ]);

        // Act
        $restCheckoutRequestAttributesTransfer = (new ShipmentTypeServicePointCheckoutRequestExpanderPlugin())
            ->setFactory($this->tester->getFactory())
            ->expand($this->getRestRequestMock(), $restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertSame(
            $originalRestShippingAddressData,
            $restCheckoutRequestAttributesTransfer->getShippingAddress()->toArray(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getRestRequestMock(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }
}
