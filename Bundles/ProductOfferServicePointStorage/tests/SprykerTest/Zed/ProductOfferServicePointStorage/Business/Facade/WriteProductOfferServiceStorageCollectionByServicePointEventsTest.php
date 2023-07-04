<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider;
use SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointStorage
 * @group Business
 * @group Facade
 * @group WriteProductOfferServiceStorageCollectionByServicePointEventsTest
 * Add your own group annotations below this line
 */
class WriteProductOfferServiceStorageCollectionByServicePointEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SERVICE_POINT_ID = -1;

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester
     */
    protected ProductOfferServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceStorageTableAndRelationsAreEmpty();
        $this->tester->setDependencies();
    }

    /**
     * @dataProvider \SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester::getProductOfferServiceDataProvider
     *
     * @param list<string> $productOfferStoreNames
     * @param array<string, mixed> $productData
     * @param array<string, mixed> $productOfferData
     * @param list<string> $servicePointStoreNames
     * @param array<string, mixed> $servicePointData
     * @param array<string, mixed> $servicesData
     * @param list<string> $productOfferServiceDataStoreNames
     * @param array<string, mixed> $productOfferServiceData
     * @param string $expectedProductOfferReference
     * @param int $expectedCount
     * @param string $expectedStore
     * @param array<string, mixed> $expectedData
     *
     * @return void
     */
    public function testWriteProductOfferServiceStorageCollectionByServiceEvents(
        array $productOfferStoreNames,
        array $productData,
        array $productOfferData,
        array $servicePointStoreNames,
        array $servicePointData,
        array $servicesData,
        array $productOfferServiceDataStoreNames,
        array $productOfferServiceData,
        string $expectedProductOfferReference,
        int $expectedCount,
        string $expectedStore,
        array $expectedData
    ): void {
        // Arrange
        [$productOfferTransfer, $servicePointTransfer] = $this->tester->createDataForProductServicePointPublishing(
            $productOfferStoreNames,
            $productData,
            $productOfferData,
            $servicePointStoreNames,
            $servicePointData,
            $servicesData,
            $productOfferServiceDataStoreNames,
            $productOfferServiceData,
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePointOrFail()),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $productOfferServiceStorageEntities = $this->tester->getProductOfferServiceStorageEntitiesByProductOfferReference(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
        );

        $this->assertCount($expectedCount, $productOfferServiceStorageEntities);
        if (!$expectedCount) {
            return;
        }

        $this->tester->assertProductOfferServiceStorageData(
            $productOfferServiceStorageEntities[0],
            $expectedStore,
            $expectedData,
        );
    }

    /**
     * @return void
     */
    public function testShouldNotPublishWhenServicePointIdsAreNotProvided(): void
    {
        // Arrange
        $servicePointFacade = $this->tester->createServicePointFacadeMock();
        $this->tester->setDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_SERVICE_POINT, $servicePointFacade);

        // Assert
        $servicePointFacade->expects($this->never())->method('getServiceCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointEvents([new EventEntityTransfer()]);
    }

    /**
     * @return void
     */
    public function testShouldNotPublishWhenServicePointDoesNotExist(): void
    {
        // Arrange
        $productOfferServiceStorageWriterMock = $this->tester->createProductOfferServiceStorageWriterMock();
        $this->tester->mockFactoryMethod('createProductOfferServiceStorageWriter', $productOfferServiceStorageWriterMock);
        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_SERVICE_POINT_ID),
        ];

        // Assert
        $productOfferServiceStorageWriterMock->expects($this->once())->method('writeProductOfferServiceStorageCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testShouldPublishByChunks(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getReadCollectionBatchSize', 1);

        $productOfferServiceStorageWriterMock = $this->tester->createProductOfferServiceStorageWriterMock();
        $this->tester->mockFactoryMethod('createProductOfferServiceStorageWriter', $productOfferServiceStorageWriterMock);

        $serviceTransfer = $this->tester->haveService();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $this->tester->haveProductOffer()->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        $secondServiceTransfer = $this->tester->haveService();

        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $this->tester->haveProductOffer()->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $secondServiceTransfer->getIdServiceOrFail(),
        ]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail()),
            (new EventEntityTransfer())->setId($secondServiceTransfer->getServicePointOrFail()->getIdServicePointOrFail()),
        ];

        // Assert
        $productOfferServiceStorageWriterMock->expects($this->exactly(2))
            ->method('writeProductOfferServiceStorageCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointEvents($eventEntityTransfers);
    }
}
