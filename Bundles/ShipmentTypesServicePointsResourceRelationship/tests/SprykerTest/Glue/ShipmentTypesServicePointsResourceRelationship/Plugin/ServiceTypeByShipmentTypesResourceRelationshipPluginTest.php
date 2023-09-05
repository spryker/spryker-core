<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypesServicePointsResourceRelationship\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\RestServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Plugin\GlueApplication\ServiceTypeByShipmentTypesResourceRelationshipPlugin;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipDependencyProvider;
use SprykerTest\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypesServicePointsResourceRelationship
 * @group Plugin
 * @group ServiceTypeByShipmentTypesResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServiceTypeByShipmentTypesResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES
     *
     * @var string
     */
    protected const RESOURCE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * @uses \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_TYPES = 'service-types';

    /**
     * @var string
     */
    protected const UUID = 'test-uuid';

    /**
     * @var string
     */
    protected const REST_ATTRIBUTES_KEY = 'test-key';

    /**
     * @var string
     */
    protected const REST_ATTRIBUTES_NAME = 'test-name';

    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var \SprykerTest\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipTester
     */
    protected ShipmentTypesServicePointsResourceRelationshipTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(
            static::SERVICE_RESOURCE_BUILDER,
            new RestResourceBuilder(),
        );
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsAddsServiceTypesRelationship(): void
    {
        // Arrange
        $this->mockServicePointsRestApiResource();

        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setServiceType((new ServiceTypeStorageTransfer())->setUuid(static::UUID));
        $restShipmentTypesResource = new RestResource(static::RESOURCE_SHIPMENT_TYPES);
        $restShipmentTypesResource->setPayload($shipmentTypeStorageTransfer);

        // Act
        (new ServiceTypeByShipmentTypesResourceRelationshipPlugin())
            ->addResourceRelationships([$restShipmentTypesResource], $this->getRestRequestMock());

        // Assert
        $this->assertCount(1, $restShipmentTypesResource->getRelationships());
        $this->assertTrue(isset($restShipmentTypesResource->getRelationships()[static::RESOURCE_SERVICE_TYPES][static::UUID]));

        $restServiceTypesResource = $restShipmentTypesResource->getRelationships()[static::RESOURCE_SERVICE_TYPES][static::UUID];
        $this->assertEquals(static::UUID, $restServiceTypesResource->getId());

        $restServiceTypesAttributesTransfer = $restServiceTypesResource->getAttributes();
        $this->assertNotNull($restServiceTypesAttributesTransfer);
        $this->assertEquals($restServiceTypesAttributesTransfer->getKey(), static::REST_ATTRIBUTES_KEY);
        $this->assertEquals($restServiceTypesAttributesTransfer->getName(), static::REST_ATTRIBUTES_NAME);
    }

    /**
     * @dataProvider addResourceRelationshipsDoesNotAddServiceTypesRelationshipsDataProvider
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return void
     */
    public function testAddResourceRelationshipsDoesNotAddServiceTypesRelationships(
        RestResourceInterface $restResource
    ): void {
        // Arrange
        $this->mockServicePointsRestApiResource();

        // Act
        (new ServiceTypeByShipmentTypesResourceRelationshipPlugin())
            ->addResourceRelationships([$restResource], $this->getRestRequestMock());

        // Assert
        $this->assertCount(0, $restResource->getRelationships());
    }

    /**
     * @return array<string, list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function addResourceRelationshipsDoesNotAddServiceTypesRelationshipsDataProvider(): array
    {
        return [
            'When wrong resource type is provided' => [
                (new RestResource(static::RESOURCE_SHIPMENT_TYPES . 'test'))->setPayload(
                    (new ShipmentTypeStorageTransfer())->setServiceType(
                        (new ServiceTypeStorageTransfer())->setUuid(static::UUID),
                    ),
                ),
            ],
            'When service type is not provided' => [
                (new RestResource(static::RESOURCE_SHIPMENT_TYPES))->setPayload(
                    new ShipmentTypeStorageTransfer(),
                ),
            ],
            'When service type UUID is not provided' => [
                (new RestResource(static::RESOURCE_SHIPMENT_TYPES))->setPayload(
                    (new ShipmentTypeStorageTransfer())->setServiceType(
                        (new ServiceTypeStorageTransfer()),
                    ),
                ),
            ],
            'When wrong service type UUID is provided' => [
                (new RestResource(static::RESOURCE_SHIPMENT_TYPES))->setPayload(
                    (new ShipmentTypeStorageTransfer())->setServiceType(
                        (new ServiceTypeStorageTransfer())->setUuid(static::UUID . 'test'),
                    ),
                ),
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getRestRequestMock(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface
     */
    protected function mockServicePointsRestApiResource(): ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface
    {
        $servicePointsRestApiResourceMock = $this->getMockBuilder(ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface::class)->getMock();
        $servicePointsRestApiResourceMock->method('getServiceTypeResourceCollection')
            ->willReturn((new ServiceTypeResourceCollectionTransfer())->addServiceTypeResource(
                (new GlueResourceTransfer())->setType(static::RESOURCE_SERVICE_TYPES)
                ->setId(static::UUID)
                ->setAttributes(
                    (new RestServiceTypesAttributesTransfer())->setKey(static::REST_ATTRIBUTES_KEY)->setName(static::REST_ATTRIBUTES_NAME),
                ),
            ));

        $this->tester->setDependency(
            ShipmentTypesServicePointsResourceRelationshipDependencyProvider::RESOURCE_SERVICE_POINTS_REST_API,
            $servicePointsRestApiResourceMock,
        );

        return $servicePointsRestApiResourceMock;
    }
}
