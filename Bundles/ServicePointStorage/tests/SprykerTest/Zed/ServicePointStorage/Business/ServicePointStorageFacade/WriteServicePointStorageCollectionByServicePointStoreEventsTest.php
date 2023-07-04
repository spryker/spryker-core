<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Business\ServicePointStorageFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Business
 * @group ServicePointStorageFacade
 * @group WriteServicePointStorageCollectionByServicePointStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteServicePointStorageCollectionByServicePointStoreEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_STORE_COL_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester
     */
    protected ServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddStoreRelation(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE, static::STORE_NAME_AT],
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
            [static::STORE_NAME_AT],
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_STORE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointStoreEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(2, $servicePointStorageEntities);

        $this->assertSame(static::STORE_NAME_AT, $servicePointStorageEntities[0]->getStore());
        $this->assertSame(static::STORE_NAME_DE, $servicePointStorageEntities[1]->getStore());
    }

    /**
     * @return void
     */
    public function testShouldRemoveStoreRelation(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_AT],
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())->fromArray($servicePointTransfer->toArray(), true),
            [static::STORE_NAME_DE, static::STORE_NAME_AT],
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_STORE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointStoreEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $this->assertSame(static::STORE_NAME_AT, $servicePointStorageEntities[0]->getStore());
    }
}
