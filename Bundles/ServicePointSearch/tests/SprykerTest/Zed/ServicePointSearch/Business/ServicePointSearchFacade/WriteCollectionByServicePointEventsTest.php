<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Business
 * @group ServicePointSearchFacade
 * @group WriteCollectionByServicePointEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByServicePointEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_STORE_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester
     */
    protected ServicePointSearchBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependencies();
        $this->tester->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsSavesSearchEntities(): void
    {
        // Arrange
        $storeDE = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $storeAT = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $firstServicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );
        $secondServicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );

        $servicePointIds = [
            $firstServicePointTransfer->getIdServicePointOrFail(),
            $secondServicePointTransfer->getIdServicePointOrFail(),
        ];

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $this->assertSame(4, $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds)->count());
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsSavesOnlyActiveSearchEntities(): void
    {
        // Arrange
        $storeDE = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $storeAT = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $firstServicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );
        $secondServicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => false],
        );

        $servicePointIds = [
            $firstServicePointTransfer->getIdServicePointOrFail(),
            $secondServicePointTransfer->getIdServicePointOrFail(),
        ];

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $this->assertSame(2, $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds)->count());
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents([]);

        // Assert
        $this->assertSame(0, $this->tester->getServicePointSearchEntitiesByServicePointIds()->count());
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsShouldCleanupSearchTable(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        $publishedServicePointSearchEntities = $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds);

        (SpyServicePointStoreQuery::create())
            ->joinWithStore()
            ->useStoreQuery()
                ->filterByName(static::TEST_STORE_AT)
            ->endUse()
            ->find()
            ->delete();

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $this->assertSame(4, $publishedServicePointSearchEntities->count());
        $this->assertSame(2, $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds)->count());
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsShouldMapServicePointToSearchData(): void
    {
        // Arrange
        $storeDE = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $servicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );

        $servicePointIds = [
            $servicePointTransfer->getIdServicePointOrFail(),
        ];

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $publishedServicePointSearchEntities = $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds);

        $data = $publishedServicePointSearchEntities->getIterator()->current()->getData();
        $structuredData = json_decode($publishedServicePointSearchEntities->getIterator()->current()->getStructuredData(), true);

        $this->tester->assertServicePointData($servicePointTransfer, $data);
        $this->tester->assertServicePointSearchData($servicePointTransfer, $data[ServicePointIndexMap::SEARCH_RESULT_DATA]);
        $this->tester->assertServicePointSearchData($servicePointTransfer, $structuredData);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointEventsShouldMapServicePointAddressToSearchData(): void
    {
        // Arrange
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations();
        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

        $servicePointIds = [
            $servicePointAddressTransfer->getServicePointOrFail()->getIdServicePointOrFail(),
        ];

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $publishedServicePointSearchEntities = $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds);

        $data = $publishedServicePointSearchEntities->getIterator()->current()->getData();
        $structuredData = json_decode($publishedServicePointSearchEntities->getIterator()->current()->getStructuredData(), true);

        $this->tester->assertServicePointAddressData($servicePointAddressTransfer, $data);
        $this->tester->assertServicePointAddressSearchData($servicePointAddressTransfer, $data[ServicePointIndexMap::SEARCH_RESULT_DATA]);
        $this->tester->assertServicePointAddressSearchData($servicePointAddressTransfer, $structuredData);
    }
}
