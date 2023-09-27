<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestServicePointsAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\ServicePointsRestApi\Plugin\GlueApplication\ServicePointsByCheckoutDataResourceRelationshipPlugin;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group ServicePointsByCheckoutDataResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServicePointsByCheckoutDataResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var \SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester
     */
    protected ServicePointsRestApiTester $tester;

    /**
     * @return void
     */
    public function _before(): void
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
    public function testAddResourceRelationshipsShouldAddRelationshipsWhenServicePointsAreProvided(): void
    {
        // Arrange
        $servicePoint1Transfer = $this->tester->createServicePointTransfer([
            ServicePointTransfer::UUID => '15c31cf2-89af-4683-b59a-9a46a0f91751',
            ServicePointTransfer::NAME => 'Service point 1',
            ServicePointTransfer::KEY => 'sp1',
        ]);

        $servicePoint2Transfer = $this->tester->createServicePointTransfer([
            ServicePointTransfer::UUID => '3ceb6350-909a-4b29-acb3-b6cc1f82e88e',
            ServicePointTransfer::NAME => 'Service point 2',
            ServicePointTransfer::KEY => 'sp2',
        ]);

        $restResources = [
            $this->tester->createCheckoutDataRestResource([$servicePoint1Transfer, $servicePoint2Transfer]),
        ];

        // Act
        (new ServicePointsByCheckoutDataResourceRelationshipPlugin())
            ->addResourceRelationships($restResources, $this->tester->createRestRequestMock());

        // Assert
        $this->assertNotEmpty($restResources[0]->getRelationships());
        $this->assertCount(2, $restResources[0]->getRelationshipByType(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS));
        foreach ($restResources[0]->getRelationshipByType(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS) as $restResource) {
            $this->assertInstanceOf(RestServicePointsAttributesTransfer::class, $restResource->getAttributes());
            $this->assertSame(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS, $restResource->getType());
            $this->assertNotEmpty($restResource->getId());
            $this->assertNotEmpty($restResource->getAttributes());
            $this->assertNotEmpty($restResource->getAttributes()[ServicePointTransfer::NAME]);
            $this->assertNotEmpty($restResource->getAttributes()[ServicePointTransfer::KEY]);
        }
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsShouldNotAddRelationshipsWhenNoServicePointsAreProvided(): void
    {
        // Arrange
        $restResources = [
            $this->tester->createCheckoutDataRestResource([]),
        ];

        // Act
        (new ServicePointsByCheckoutDataResourceRelationshipPlugin())
            ->addResourceRelationships($restResources, $this->tester->createRestRequestMock());

        // Assert
        $this->assertEmpty($restResources[0]->getRelationships());
    }
}
