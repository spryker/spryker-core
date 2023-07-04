<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
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
 * @group WriteProductOfferServiceStorageCollectionByProductOfferStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteProductOfferServiceStorageCollectionByProductOfferStoreEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_PRODUCT_OFFER_ID = -1;

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_SERVICE_POINT = 'spy_product_offer_store.fk_product_offer';

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
    public function testWriteProductOfferServiceStorageCollectionByProductOfferStoreEvents(
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
        [$productOfferTransfer] = $this->tester->createDataForProductServicePointPublishing(
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
                static::COL_FK_SERVICE_POINT => $productOfferTransfer->getIdProductOfferOrFail(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByProductOfferStoreEvents($eventEntityTransfers);

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
    public function testShouldNotPublishWhenProductOfferIdsAreNotProvided(): void
    {
        // Arrange
        $productOfferServicePointFacade = $this->tester->createProductOfferServicePointFacadeMock();
        $this->tester->setDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_PRODUCT_OFFER_SERVICE_POINT, $productOfferServicePointFacade);

        // Assert
        $productOfferServicePointFacade->expects($this->never())->method('iterateProductOfferServices');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByProductOfferStoreEvents([new EventEntityTransfer()]);
    }

    /**
     * @return void
     */
    public function testShouldNotPublishWhenProductOfferDoesNotExist(): void
    {
        // Arrange
        $productOfferServiceStorageWriterMock = $this->tester->createProductOfferServiceStorageWriterMock();
        $this->tester->mockFactoryMethod('createProductOfferServiceStorageWriter', $productOfferServiceStorageWriterMock);
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_SERVICE_POINT => static::FAKE_PRODUCT_OFFER_ID,
            ]),
        ];

        // Assert
        $productOfferServiceStorageWriterMock->expects($this->once())->method('writeProductOfferServiceStorageCollection');

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByProductOfferStoreEvents($eventTransfers);
    }
}
