<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentsRestApi\Processor;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Plugin\GlueApplication\ShipmentMethodsByShipmentResourceRelationshipPlugin;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use SprykerTest\Glue\ShipmentsRestApi\ShipmentsRestApiProcessorTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentsRestApi
 * @group Processor
 * @group ShipmentMethodsByShipmentResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ShipmentMethodsByShipmentResourceRelationshipPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var int
     */
    protected const ID_SHIPMENT_METHOD = 1;

    /**
     * @var string
     */
    protected const REST_RESOURCE_ID = '1';

    /**
     * @var \SprykerTest\Glue\ShipmentsRestApi\ShipmentsRestApiProcessorTester
     */
    protected ShipmentsRestApiProcessorTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(static::SERVICE_RESOURCE_BUILDER, new RestResourceBuilder());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsShouldAddResourcesWithPayload(): void
    {
        // Arrange
        $restResource = new RestResource(
            ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
            static::REST_RESOURCE_ID,
            new RestShipmentsAttributesTransfer(),
        );

        $shipmentMethodTransfer = (new ShipmentMethodBuilder())->build()
            ->setIdShipmentMethod(static::ID_SHIPMENT_METHOD);

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())
            ->setAvailableShipmentMethods((new ShipmentMethodsTransfer())->addMethod($shipmentMethodTransfer));

        $restResource->setPayload($shipmentGroupTransfer);
        $restRequest = Stub::makeEmpty(RestRequestInterface::class);

        // Act
        (new ShipmentMethodsByShipmentResourceRelationshipPlugin())->addResourceRelationships([$restResource], $restRequest);

        // Assert
        $shipmentMethodsRestResource = $restResource->getRelationshipByType(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS)[static::REST_RESOURCE_ID];
        $this->assertInstanceOf(ShipmentMethodTransfer::class, $shipmentMethodsRestResource->getPayload());
        $this->assertSame(static::ID_SHIPMENT_METHOD, $shipmentMethodsRestResource->getPayload()->getIdShipmentMethod());
    }
}
