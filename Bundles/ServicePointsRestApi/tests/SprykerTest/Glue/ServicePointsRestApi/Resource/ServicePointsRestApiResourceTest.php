<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi\Resource;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ServiceTypeStorageBuilder;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\RestServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiDependencyProvider;
use SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiResourceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsRestApi
 * @group Resource
 * @group ServicePointsRestApiResourceTest
 * Add your own group annotations below this line
 */
class ServicePointsRestApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_UUID_1 = 'service-type-uuid-1';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_UUID_2 = 'service-type-uuid-2';

    /**
     * @var \SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiResourceTester
     */
    protected ServicePointsRestApiResourceTester $tester;

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
    public function testGetServiceTypeResourceCollectionReturnsCollectionOfGlueResourcesWithCorrectAttributes(): void
    {
        // Arrange
        $serviceTypeStorageCollectionTransfer = $this->createServiceTypeStorageCollectionTransfer([
            static::TEST_SERVICE_TYPE_UUID_1,
            static::TEST_SERVICE_TYPE_UUID_2,
        ]);

        $this->tester->setDependency(
            ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE,
            $this->createServicePointStorageClientMock($serviceTypeStorageCollectionTransfer),
        );

        $serviceTypeResourceConditionsTransfer = (new ServiceTypeResourceConditionsTransfer())
            ->addUuid(static::TEST_SERVICE_TYPE_UUID_1)
            ->addUuid(static::TEST_SERVICE_TYPE_UUID_2);
        $serviceTypeResourceCriteriaTransfer = (new ServiceTypeResourceCriteriaTransfer())
            ->setServiceTypeResourceConditions($serviceTypeResourceConditionsTransfer);

        // Act
        $serviceTypeResourceCollectionTransfer = $this->tester->getResource()
            ->getServiceTypeResourceCollection($serviceTypeResourceCriteriaTransfer);

        // Assert
        $this->assertCount(2, $serviceTypeResourceCollectionTransfer->getServiceTypeResources());
        $serviceTypeResourceTransfer = $this->findServiceTypeResourceByUuid($serviceTypeResourceCollectionTransfer, static::TEST_SERVICE_TYPE_UUID_1);
        $this->assertNotNull($serviceTypeResourceTransfer);
        $this->assertInstanceOf(RestServiceTypesAttributesTransfer::class, $serviceTypeResourceTransfer->getAttributes());

        $serviceTypeStorageTransfer = $serviceTypeStorageCollectionTransfer->getServiceTypeStorages()->offsetGet(0);
        $this->assertSame($serviceTypeStorageTransfer->getNameOrFail(), $serviceTypeResourceTransfer->getAttributes()->getName());
        $this->assertSame($serviceTypeStorageTransfer->getKeyOrFail(), $serviceTypeResourceTransfer->getAttributes()->getKey());
    }

    /**
     * @return void
     */
    public function testGetServiceTypeResourceCollectionReturnsEmptyCollectionWhenNoStorageDataFound(): void
    {
        // Arrange
        $serviceTypeStorageCollectionTransfer = $this->createServiceTypeStorageCollectionTransfer([]);

        $this->tester->setDependency(
            ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE,
            $this->createServicePointStorageClientMock($serviceTypeStorageCollectionTransfer),
        );

        $serviceTypeResourceConditionsTransfer = (new ServiceTypeResourceConditionsTransfer())
            ->addUuid(static::TEST_SERVICE_TYPE_UUID_1);
        $serviceTypeResourceCriteriaTransfer = (new ServiceTypeResourceCriteriaTransfer())
            ->setServiceTypeResourceConditions($serviceTypeResourceConditionsTransfer);

        // Act
        $serviceTypeResourceCollectionTransfer = $this->tester->getResource()
            ->getServiceTypeResourceCollection($serviceTypeResourceCriteriaTransfer);

        // Assert
        $this->assertCount(0, $serviceTypeResourceCollectionTransfer->getServiceTypeResources());
    }

    /**
     * @dataProvider getServiceTypeResourceCollectionCallsClientWithEmptyServiceTypeStorageConditionsTransferSetDataProvider
     *
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
     *
     * @return void
     */
    public function testGetServiceTypeResourceCollectionCallsClientWithEmptyServiceTypeStorageConditionsTransferSet(
        ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
    ): void {
        // Assert
        $this->assertGetServiceTypeStorageCollectionEmptyConditions();

        // Act
        $this->tester->getResource()->getServiceTypeResourceCollection($serviceTypeResourceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer $serviceTypeStorageCollectionTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface
     */
    protected function createServicePointStorageClientMock(
        ServiceTypeStorageCollectionTransfer $serviceTypeStorageCollectionTransfer
    ): ServicePointsRestApiToServicePointStorageClientInterface {
        $servicePointStorageClientMock = $this->getMockBuilder(ServicePointsRestApiToServicePointStorageClientInterface::class)
            ->getMock();

        $servicePointStorageClientMock
            ->method('getServiceTypeStorageCollection')
            ->willReturn($serviceTypeStorageCollectionTransfer);

        return $servicePointStorageClientMock;
    }

    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    protected function createServiceTypeStorageCollectionTransfer(array $serviceTypeUuids): ServiceTypeStorageCollectionTransfer
    {
        $serviceTypeStorageCollectionTransfer = new ServiceTypeStorageCollectionTransfer();
        foreach ($serviceTypeUuids as $serviceTypeUuid) {
            $serviceTypeStorageTransfer = (new ServiceTypeStorageBuilder([
                ServiceTypeStorageTransfer::UUID => $serviceTypeUuid,
            ]))->build();
            $serviceTypeStorageCollectionTransfer->addServiceTypeStorage($serviceTypeStorageTransfer);
        }

        return $serviceTypeStorageCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer|null
     */
    protected function findServiceTypeResourceByUuid(
        ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer,
        string $uuid
    ): ?GlueResourceTransfer {
        foreach ($serviceTypeResourceCollectionTransfer->getServiceTypeResources() as $serviceTypeResource) {
            if ($serviceTypeResource->getId() === $uuid) {
                return $serviceTypeResource;
            }
        }

        return null;
    }

    /**
     * @return void
     */
    protected function assertGetServiceTypeStorageCollectionEmptyConditions(): void
    {
        $servicePointStorageClientMock = Stub::makeEmpty(ServicePointsRestApiToServicePointStorageClientInterface::class, [
            'getServiceTypeStorageCollection' => function (ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer) {
                $this->assertNotNull($serviceTypeStorageCriteriaTransfer->getServiceTypeStorageConditions());
                $this->assertEmpty($serviceTypeStorageCriteriaTransfer->getServiceTypeStorageConditions()->getUuids());

                return new ServiceTypeStorageCollectionTransfer();
            },
        ]);

        $this->tester->setDependency(
            ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE,
            $servicePointStorageClientMock,
        );
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer>>
     */
    protected function getServiceTypeResourceCollectionCallsClientWithEmptyServiceTypeStorageConditionsTransferSetDataProvider(): array
    {
        return [
            'When conditions transfer is not provided' => [new ServiceTypeResourceCriteriaTransfer()],
            'When empty conditions transfer is provided' => [(new ServiceTypeResourceCriteriaTransfer())
                ->setServiceTypeResourceConditions(
                    new ServiceTypeResourceConditionsTransfer(),
                )],
        ];
    }
}
