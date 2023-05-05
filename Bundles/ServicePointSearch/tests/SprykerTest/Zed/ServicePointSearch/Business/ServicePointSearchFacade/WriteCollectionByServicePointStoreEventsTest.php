<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
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
 * @group WriteCollectionByServicePointStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByServicePointStoreEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_SERVICE_POINT_STORE_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

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
    public function testWriteCollectionByServicePointStoreEventsShouldUpdateServicePointSearchEntities(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createPublishedServicePointForTwoStores();
        $idServicePoint = $servicePointTransfer->getIdServicePointOrFail();

        (SpyServicePointStoreQuery::create())
            ->filterByFkServicePoint($idServicePoint)
            ->joinWithStore()
            ->useStoreQuery()
                ->filterByName(static::TEST_STORE_AT)
            ->endUse()
            ->find()
            ->delete();

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromForeignKeys(
            static::COL_SERVICE_POINT_STORE_FK_SERVICE_POINT,
            [$idServicePoint],
        );

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointStoreEvents($eventEntityTransfers);

        // Assert
        $this->assertCount(1, $this->tester->getServicePointSearchEntitiesByServicePointIds([$idServicePoint]));
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointStoreEventsShouldRemoveAllServicePointSearchEntities(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createPublishedServicePointForTwoStores();
        $idServicePoint = $servicePointTransfer->getIdServicePointOrFail();

        (SpyServicePointStoreQuery::create())
            ->filterByFkServicePoint($idServicePoint)
            ->find()
            ->delete();

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromForeignKeys(
            static::COL_SERVICE_POINT_STORE_FK_SERVICE_POINT,
            [$idServicePoint],
        );

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointStoreEvents($eventEntityTransfers);

        // Assert
        $this->assertCount(0, $this->tester->getServicePointSearchEntitiesByServicePointIds([$idServicePoint]));
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointStoreEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Act
        $this->tester->getFacade()->writeCollectionByServicePointStoreEvents([]);

        // Assert
        $this->assertSame(0, $this->tester->getServicePointSearchEntitiesByServicePointIds()->count());
    }
}
