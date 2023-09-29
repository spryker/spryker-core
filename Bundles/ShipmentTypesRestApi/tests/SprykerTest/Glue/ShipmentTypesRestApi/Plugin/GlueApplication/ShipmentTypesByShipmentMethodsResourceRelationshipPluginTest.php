<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypesRestApi\Plugin\GlueApplication;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequest;
use Spryker\Glue\ShipmentTypesRestApi\Plugin\GlueApplication\ShipmentTypesByShipmentMethodsResourceRelationshipPlugin;
use SprykerTest\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypesRestApi
 * @group Plugin
 * @group GlueApplication
 * @group ShipmentTypesByShipmentMethodsResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypesByShipmentMethodsResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS
     *
     * @var string
     */
    protected const RESOURCE_TYPE_SHIPMENT_METHODS = 'shipment-methods';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_UUID = 'shipment-type-uuid';

    /**
     * @var \SprykerTest\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiPluginTester
     */
    protected ShipmentTypesRestApiPluginTester $tester;

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
    public function testAddResourceRelationshipsShouldAddResourceRelationshipWhenResourceHasShipmentType(): void
    {
        // Arrange
        $restResource = new RestResource(static::RESOURCE_TYPE_SHIPMENT_METHODS, '1');
        $restResource->setPayload(
            (new ShipmentMethodTransfer())
                ->setShipmentType((new ShipmentTypeTransfer())->setUuid(static::SHIPMENT_TYPE_UUID)),
        );

        $restRequest = Stub::make(RestRequest::class);

        // Act
        (new ShipmentTypesByShipmentMethodsResourceRelationshipPlugin())->addResourceRelationships([$restResource], $restRequest);

        // Assert
        $this->assertCount(1, $restResource->getRelationshipByType(static::RESOURCE_TYPE_SHIPMENT_TYPES));
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsShouldNotAddResourceRelationshipWhenResourceHasNoPayload(): void
    {
        // Arrange
        $restResource = new RestResource(static::RESOURCE_TYPE_SHIPMENT_METHODS, '1');
        $restRequest = Stub::make(RestRequest::class);

        // Act
        (new ShipmentTypesByShipmentMethodsResourceRelationshipPlugin())->addResourceRelationships([$restResource], $restRequest);

        // Assert
        $this->assertEmpty($restResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsShouldNotAddResourceRelationshipWhenResourceHasWrongPayload(): void
    {
        // Arrange
        $restResource = new RestResource(static::RESOURCE_TYPE_SHIPMENT_METHODS, '1');
        $restResource->setPayload(new ShipmentTypeTransfer());
        $restRequest = Stub::make(RestRequest::class);

        // Act
        (new ShipmentTypesByShipmentMethodsResourceRelationshipPlugin())->addResourceRelationships([$restResource], $restRequest);

        // Assert
        $this->assertEmpty($restResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsShouldNotAddResourceRelationshipWhenResourceHasPayloadWithoutShipmentType(): void
    {
        // Arrange
        $restResource = new RestResource(static::RESOURCE_TYPE_SHIPMENT_METHODS, '1');
        $restResource->setPayload(new ShipmentMethodTransfer());
        $restRequest = Stub::make(RestRequest::class);

        // Act
        (new ShipmentTypesByShipmentMethodsResourceRelationshipPlugin())->addResourceRelationships([$restResource], $restRequest);

        // Assert
        $this->assertEmpty($restResource->getRelationships());
    }
}
