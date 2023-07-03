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
use SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Business
 * @group ServicePointSearchFacade
 * @group WriteCollectionByServicePointAddressEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByServicePointAddressEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointAddressTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_SERVICE_POINT_ADDRESS_FK_SERVICE_POINT = 'spy_service_point_address.fk_service_point';

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
    public function testWriteCollectionByServicePointAddressEventsShouldPublishServicePointSearchEntities(): void
    {
        // Arrange
        $storeDE = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);

        $servicePointTransfer = $this->tester->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );
        $servicePointTransfer = $this->tester->addServicePointAddressForServicePoint($servicePointTransfer);

        $idServicePoint = $servicePointTransfer->getIdServicePoint();

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromForeignKeys(
            static::COL_SERVICE_POINT_ADDRESS_FK_SERVICE_POINT,
            [$idServicePoint],
        );

        // Act
        $this->tester->getFacade()->writeCollectionByServicePointAddressEvents($eventEntityTransfers);

        // Assert
        $publishedServicePointSearchEntities = $this->tester->getServicePointSearchEntitiesByServicePointIds(
            [$idServicePoint],
            [static::TEST_STORE_DE],
        );

        $this->assertCount(1, $publishedServicePointSearchEntities);

        $data = $publishedServicePointSearchEntities->getIterator()->current()->getData();
        $structuredData = json_decode($publishedServicePointSearchEntities->getIterator()->current()->getStructuredData(), true);

        $this->tester->assertServicePointData($servicePointTransfer, $data);
        $this->tester->assertServicePointAddressSearchData($servicePointTransfer->getAddressOrFail(), $data[ServicePointIndexMap::SEARCH_RESULT_DATA]);
        $this->tester->assertServicePointAddressSearchData($servicePointTransfer->getAddressOrFail(), $structuredData);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServicePointAddressEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Act
        $this->tester->getFacade()->writeCollectionByServicePointAddressEvents([]);

        // Assert
        $this->assertSame(0, $this->tester->getServicePointSearchEntitiesByServicePointIds()->count());
    }
}
