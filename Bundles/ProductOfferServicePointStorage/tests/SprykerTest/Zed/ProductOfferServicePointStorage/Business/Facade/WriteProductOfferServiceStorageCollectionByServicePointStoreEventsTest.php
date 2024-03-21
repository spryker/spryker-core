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
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointStorage
 * @group Business
 * @group Facade
 * @group WriteProductOfferServiceStorageCollectionByServicePointStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteProductOfferServiceStorageCollectionByServicePointStoreEventsTest extends Unit
{
    use BusinessHelperTrait;
    use ConfigHelperTrait;

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

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
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePointOrFail(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointStoreEvents($eventEntityTransfers);

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
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointStoreEvents([new EventEntityTransfer()]);
    }

    /**
     * @return void
     */
    public function testShouldNotPublishWhenServicePointDoesNotExist(): void
    {
        // Arrange
        $productOfferServiceStorageWriterMock = $this->tester->createProductOfferServiceStorageWriterMock();
        $this->getBusinessHelper()->mockFactoryMethod('createProductOfferServiceStorageWriter', $productOfferServiceStorageWriterMock);
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_SERVICE_POINT => static::FAKE_SERVICE_POINT_ID,
            ]),
        ];

        // Assert
        $productOfferServiceStorageWriterMock->expects($this->once())->method('writeProductOfferServiceStorageCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointStoreEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testShouldPublishByChunks(): void
    {
        // Arrange
        $this->getConfigHelper()->mockConfigMethod('getReadCollectionBatchSize', 1);

        $productOfferServiceStorageWriterMock = $this->tester->createProductOfferServiceStorageWriterMock();
        $this->getBusinessHelper()->mockFactoryMethod('createProductOfferServiceStorageWriter', $productOfferServiceStorageWriterMock);

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
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_SERVICE_POINT => $serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail(),
            ]),
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_SERVICE_POINT => $secondServiceTransfer->getServicePointOrFail()->getIdServicePointOrFail(),
            ]),
        ];

        // Assert
        $productOfferServiceStorageWriterMock->expects($this->exactly(2))
            ->method('writeProductOfferServiceStorageCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByServicePointStoreEvents($eventEntityTransfers);
    }
}
