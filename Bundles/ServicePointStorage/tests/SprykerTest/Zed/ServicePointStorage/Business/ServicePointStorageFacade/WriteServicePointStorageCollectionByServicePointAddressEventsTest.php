<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage\Business\ServicePointStorageFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ServicePointAddressStorageTransfer;
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
 * @group WriteServicePointStorageCollectionByServicePointAddressEventsTest
 * Add your own group annotations below this line
 */
class WriteServicePointStorageCollectionByServicePointAddressEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointAddressTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT = 'spy_service_point_address.fk_service_point';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_UUID = 'TEST_UUID';

    /**
     * @var string
     */
    protected const KEY_UUID = 'uuid';

    /**
     * @var string
     */
    protected const KEY_ADDRESS = 'address';

    /**
     * @var string
     */
    protected const KEY_ISO_2_CODE = 'iso2_code';

    /**
     * @var string
     */
    protected const KEY_COUNTRY = 'country';

    /**
     * @var string
     */
    protected const KEY_REGION = 'region';

    /**
     * @var \SprykerTest\Zed\ServicePointStorage\ServicePointStorageBusinessTester
     */
    protected ServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldAddAddress(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            [static::STORE_NAME_DE],
        );

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransfer($servicePointTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointAddressEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntityData = $servicePointStorageEntities[0]->getData();
        $this->assertSame($servicePointAddressTransfer->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_UUID]);
        $this->assertSame($servicePointAddressTransfer->getCountry()->getIso2Code(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_COUNTRY][static::KEY_ISO_2_CODE]);
        $this->assertSame($servicePointAddressTransfer->getRegion()->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_REGION][static::KEY_UUID]);
    }

    /**
     * @return void
     */
    public function testShouldUpdateAddress(): void
    {
        // Arrange
        $storeNames = [static::STORE_NAME_DE];
        $servicePointTransfer = $this->tester->createServicePointTransferWithStoreRelations(
            [ServicePointTransfer::IS_ACTIVE => true],
            $storeNames,
        );

        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransfer($servicePointTransfer);

        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->fromArray($servicePointTransfer->toArray(), true)
            ->setAddress(
                (new ServicePointAddressStorageTransfer())
                    ->fromArray($servicePointAddressTransfer->toArray(), true)
                    ->setUuid(static::TEST_UUID)
                    ->setCountry()
                    ->setRegion(),
            );

        $this->tester->createServicePointStorageByStoreRelations($servicePointStorageTransfer, $storeNames);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT => $servicePointTransfer->getIdServicePoint(),
        ]);

        // Act
        $this->tester->getFacade()->writeServicePointStorageCollectionByServicePointAddressEvents([$eventEntityTransfer]);

        // Assert
        $servicePointStorageEntities = $this->tester->getServicePointStorageEntitiesByIdServicePoint($servicePointTransfer->getIdServicePoint());
        $this->assertCount(1, $servicePointStorageEntities);

        $servicePointEntityData = $servicePointStorageEntities[0]->getData();
        $this->assertSame($servicePointAddressTransfer->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_UUID]);
        $this->assertSame($servicePointAddressTransfer->getCountry()->getIso2Code(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_COUNTRY][static::KEY_ISO_2_CODE]);
        $this->assertSame($servicePointAddressTransfer->getRegion()->getUuid(), $servicePointEntityData[static::KEY_ADDRESS][static::KEY_REGION][static::KEY_UUID]);
    }
}
