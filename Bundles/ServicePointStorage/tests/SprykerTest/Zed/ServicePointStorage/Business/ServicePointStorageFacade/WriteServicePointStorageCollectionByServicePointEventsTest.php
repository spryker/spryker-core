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
 * @group WriteServicePointStorageCollectionByServicePointEventsTest
 * Add your own group annotations below this line
 */
class WriteServicePointStorageCollectionByServicePointEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var int
     */
    protected const FAKE_ID_SERVICE_POINT = -1;

    /**
     * @var string
     */
    protected const TEST_UUID = 'TEST_UUID';

    /**
     * @var string
     */
    protected const TEST_UUID_2 = 'TEST_UUID_2';

    /**
     * @var string
     */
    protected const KEY_UUID = 'uuid';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester
     */
    protected ServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldNotWriteWhenServicePointIdIsNotProvided(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([new EventEntityTransfer()]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testShouldNotWriteWhenNotExistingServicePointIdIsProvided(): void
    {
        // Arrange
        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->setIsActive(true)
                ->setIdServicePoint(static::FAKE_ID_SERVICE_POINT),
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId(static::FAKE_ID_SERVICE_POINT);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint(static::FAKE_ID_SERVICE_POINT);
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testShouldNotWriteNotActiveServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => false],
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testShouldWriteActiveServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );
        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntity = $servicePointStorageEntities[0];
        $this->assertSame(static::STORE_NAME_DE, $servicePointEntity->getStore());
        $this->assertSame($servicePointTransfer->getUuid(), $servicePointEntity->getData()[static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testShouldDeleteServicePointWhenServicePointIsDeactivated(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => false],
            $storeNames,
        );

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->fromArray($servicePointTransfer->toArray(), true)
                ->setIsActive(true),
            $storeNames,
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(0, $servicePointStorageEntities);
    }

    /**
     * @return void
     */
    public function testShouldUpdateServicePoint(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::UUID => static::TEST_UUID,
        ], $storeNames);

        $this->tester->createServicePointStorageByStoreRelations(
            (new ServicePointStorageTransfer())
                ->fromArray($servicePointTransfer->toArray(), true)
                ->setUuid(static::TEST_UUID_2),
            $storeNames,
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($servicePointTransfer->getIdServicePoint());

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntity = $servicePointStorageEntities[0];
        $this->assertSame(static::STORE_NAME_DE, $servicePointEntity->getStore());
        $this->assertSame($servicePointTransfer->getUuid(), $servicePointEntity->getData()[static::KEY_UUID]);
    }
}
