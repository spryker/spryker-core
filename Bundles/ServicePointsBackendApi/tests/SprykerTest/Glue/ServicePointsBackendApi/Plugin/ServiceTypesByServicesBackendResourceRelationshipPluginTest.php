<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiServicesAttributesTransfer;
use Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeBridge;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ServiceTypesByServicesBackendResourceRelationshipPlugin;
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
 * @group ServiceTypesByServicesBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServiceTypesByServicesBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_TYPE_UUID = 'service-type-uuid';

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
    public function testAddRelationshipsShouldAddServiceTypesRelationshipToServiceGlueResourceTransfer(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID);

        $serviceTransfer = (new ServiceTransfer())
            ->setUuid(static::SERVICE_UUID)
            ->setServiceType($serviceTypeTransfer);
        $this->mockServicePointFacade([$serviceTransfer], [$serviceTypeTransfer]);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
                ->setAttributes(new ApiServicesAttributesTransfer()),
        ];

        // Act
        (new ServiceTypesByServicesBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfer->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfer->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            ServicePointsBackendApiConfig::RESOURCE_SERVICE_TYPES,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(ApiServiceTypesAttributesTransfer::class, $glueResourceTransfer->getAttributes());

        $this->assertSame(static::SERVICE_TYPE_UUID, $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddServicePointsRelationshipToServiceGlueResourceTransferWhenServiceHasNoRelatedServicePoint(): void
    {
        // Arrange
        $serviceTypeTransfer = (new ServiceTypeTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID);

        $serviceTransfer = (new ServiceTransfer())
            ->setUuid(static::SERVICE_UUID)
            ->setServiceType($serviceTypeTransfer);
        $this->mockServicePointFacade([$serviceTransfer], []);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
                ->setAttributes(new ApiServicesAttributesTransfer()),
        ];

        // Act
        (new ServiceTypesByServicesBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfer->getRelationships());
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     * @param list<\Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     *
     * @return void
     */
    protected function mockServicePointFacade(array $serviceTransfers, array $serviceTypeTransfers): void
    {
        $servicePointFacade = $this->getMockBuilder(ServicePointsBackendApiToServicePointFacadeBridge::class)
            ->onlyMethods([
                'getServiceCollection',
                'getServiceTypeCollection',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $servicePointFacade->method('getServiceCollection')->willReturn(
            (new ServiceCollectionTransfer())->setServices(new ArrayObject($serviceTransfers)),
        );

        $servicePointFacade->method('getServiceTypeCollection')->willReturn(
            (new ServiceTypeCollectionTransfer())->setServiceTypes(new ArrayObject($serviceTypeTransfers)),
        );

        $this->tester->setDependency(ServicePointsBackendApiDependencyProvider::FACADE_SERVICE_POINT, $servicePointFacade);
    }
}
