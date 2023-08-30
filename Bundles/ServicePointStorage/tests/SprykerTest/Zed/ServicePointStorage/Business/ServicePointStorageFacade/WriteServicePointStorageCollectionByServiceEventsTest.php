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
use Generated\Shared\Transfer\ServiceStorageTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointStorage
 * @group Business
 * @group ServicePointStorageFacade
 * @group WriteServicePointStorageCollectionByServiceEventsTest
 * Add your own group annotations below this line
 */
class WriteServicePointStorageCollectionByServiceEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_COL_FK_SERVICE_POINT = 'spy_service.fk_service_point';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const KEY_SERVICES = 'services';

    /**
     * @var string
     */
    protected const KEY_ID_SERVICE = 'id_service';

    /**
     * @var string
     */
    protected const KEY_UUID = 'uuid';

    /**
     * @var string
     */
    protected const KEY_KEY = 'key';

    /**
     * @var string
     */
    protected const KEY_SERVICE_TYPE = 'service_type';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester
     */
    protected ServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddActiveServices(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );

        $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => true,
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServiceEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicesData = $servicePointStorageEntities[0]->getData()[static::KEY_SERVICES];
        $this->assertCount(1, $servicesData);

        $serviceData = $servicesData[0];
        $this->assertSame($serviceTransfer->getIdService(), $serviceData[static::KEY_ID_SERVICE]);
        $this->assertSame($serviceTransfer->getUuid(), $serviceData[static::KEY_UUID]);
        $this->assertSame($serviceTransfer->getKey(), $serviceData[static::KEY_KEY]);
        $this->assertSame($serviceTransfer->getServiceType()->getUuid(), $serviceData[static::KEY_SERVICE_TYPE][static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testShouldRemoveNotActiveService(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            $storeNames,
        );

        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => false,
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->fromArray($servicePointTransfer->toArray(), true)
            ->addService(
                (new ServiceStorageTransfer())->fromArray($serviceTransfer->toArray(), true),
            );

        $this->tester->createServicePointStorageByStoreRelations($servicePointStorageTransfer, $storeNames);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServiceEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicesData = $servicePointStorageEntities[0]->getData()[static::KEY_SERVICES];
        $this->assertCount(0, $servicesData);
    }
}
