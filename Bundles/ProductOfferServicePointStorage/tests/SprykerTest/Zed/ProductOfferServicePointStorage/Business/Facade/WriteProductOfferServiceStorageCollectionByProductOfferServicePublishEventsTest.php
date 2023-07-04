<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointStorage
 * @group Business
 * @group Facade
 * @group WriteProductOfferServiceStorageCollectionByProductOfferServicePublishEventsTest
 * Add your own group annotations below this line
 */
class WriteProductOfferServiceStorageCollectionByProductOfferServicePublishEventsTest extends Unit
{
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
    public function testWriteProductOfferServiceStorageCollectionByProductOfferServiceUnpublishEvents(
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
        [$productOfferTransfer, , , $productOfferServiceTransfers] = $this->tester->createDataForProductServicePointPublishing(
            $productOfferStoreNames,
            $productData,
            $productOfferData,
            $servicePointStoreNames,
            $servicePointData,
            $servicesData,
            $productOfferServiceDataStoreNames,
            $productOfferServiceData,
        );

        $eventEntityTransfers = [];
        foreach ($productOfferServiceTransfers as $productOfferServiceTransfer) {
            $eventEntityTransfers[] = (new EventEntityTransfer())->setId($this->tester->findIdProductOfferService($productOfferServiceTransfer));
        }

        // Act
        $this->tester->getFacade()->writeProductOfferServiceStorageCollectionByProductOfferServicePublishEvents($eventEntityTransfers);

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
}
