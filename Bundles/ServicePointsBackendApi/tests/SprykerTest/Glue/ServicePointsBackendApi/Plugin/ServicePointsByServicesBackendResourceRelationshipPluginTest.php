<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeBridge;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ServicePointsByServicesBackendResourceRelationshipPlugin;
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
 * @group ServicePointsByServicesBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServicePointsByServicesBackendResourceRelationshipPluginTest extends Unit
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
     * @var string
     */
    protected const STORE_NAME = 'DE';

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
            ->setUuid(static::SERVICE_POINT_UUID)
            ->setStoreRelation(
                (new StoreRelationTransfer())->addStores(
                    (new StoreTransfer())->setName(static::STORE_NAME),
                ),
            );
        $serviceTransfer = (new ServiceTransfer())
            ->setUuid(static::SERVICE_UUID)
            ->setServicePoint($servicePointTransfer);
        $this->mockServicePointFacade([$serviceTransfer], [$servicePointTransfer]);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
                ->setAttributes(new ServicesBackendApiAttributesTransfer()),
        ];

        // Act
        (new ServicePointsByServicesBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfer->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfer->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(ServicePointsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());

        $this->assertSame(static::SERVICE_POINT_UUID, $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddServicePointsRelationshipToServiceGlueResourceTransferWhenServiceHasNoRelatedServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointTransfer())
            ->setUuid(static::SERVICE_POINT_UUID)
            ->setStoreRelation(
                (new StoreRelationTransfer())->addStores(
                    (new StoreTransfer())->setName(static::STORE_NAME),
                ),
            );
        $serviceTransfer = (new ServiceTransfer())
            ->setUuid(static::SERVICE_UUID)
            ->setServicePoint($servicePointTransfer);
        $this->mockServicePointFacade([$serviceTransfer], []);

        $glueRequestTransfer = new GlueRequestTransfer();
        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
                ->setAttributes(new ServicesBackendApiAttributesTransfer()),
        ];

        // Act
        (new ServicePointsByServicesBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfer->getRelationships());
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     * @param list<\Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return void
     */
    protected function mockServicePointFacade(array $serviceTransfers, array $servicePointTransfers): void
    {
        $servicePointFacade = $this->getMockBuilder(ServicePointsBackendApiToServicePointFacadeBridge::class)
            ->onlyMethods([
                'getServiceCollection',
                'getServicePointCollection',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $servicePointFacade->method('getServiceCollection')->willReturn(
            (new ServiceCollectionTransfer())->setServices(new ArrayObject($serviceTransfers)),
        );

        $servicePointFacade->method('getServicePointCollection')->willReturn(
            (new ServicePointCollectionTransfer())->setServicePoints(new ArrayObject($servicePointTransfers)),
        );

        $this->tester->setDependency(ServicePointsBackendApiDependencyProvider::FACADE_SERVICE_POINT, $servicePointFacade);
    }
}
