<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiServicesAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeBridge;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ServicesByServicePointsBackendResourceRelationshipPlugin;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiDependencyProvider;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServicesByServicePointsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServicesByServicePointsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID = 'service-point-uuid';

    /**
     * @var string
     */
    protected const SERVICE_UUID = 'service-uuid';

    /**
     * @var \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester
     */
    protected ServicePointsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddServicePointsRelationshipToServiceGlueResourceTransfer(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointTransfer())
            ->setUuid(static::SERVICE_POINT_UUID);

        $this->mockServicePointFacade([
            (new ServiceTransfer())
                ->setUuid(static::SERVICE_UUID)
                ->setServicePoint($servicePointTransfer),
        ]);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_POINT_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
                ->setAttributes(new ApiServicesAttributesTransfer()),
        ];

        // Act
        (new ServicesByServicePointsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfer->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfer->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            ServicePointsBackendApiConfig::RESOURCE_SERVICES,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(ApiServicesAttributesTransfer::class, $glueResourceTransfer->getAttributes());

        $this->assertSame(static::SERVICE_UUID, $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddServicePointsRelationshipToServiceGlueResourceTransferWhenServiceHasNoRelatedServicePoint(): void
    {
        // Arrange
        $this->mockServicePointFacade([]);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_POINT_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
                ->setAttributes(new ApiServicesAttributesTransfer()),
        ];

        // Act
        (new ServicesByServicePointsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfer->getRelationships());
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return void
     */
    protected function mockServicePointFacade(array $serviceTransfers): void
    {
        $servicePointFacade = $this->getMockBuilder(ServicePointsBackendApiToServicePointFacadeBridge::class)
            ->onlyMethods([
                'getServiceCollection',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $servicePointFacade->method('getServiceCollection')->willReturn(
            (new ServiceCollectionTransfer())->setServices(new ArrayObject($serviceTransfers)),
        );

        $this->tester->setDependency(ServicePointsBackendApiDependencyProvider::FACADE_SERVICE_POINT, $servicePointFacade);
    }
}
