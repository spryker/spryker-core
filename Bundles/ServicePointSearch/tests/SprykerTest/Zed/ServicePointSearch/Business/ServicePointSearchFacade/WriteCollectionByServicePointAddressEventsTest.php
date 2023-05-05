<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
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
    public function testWriteCollectionByServicePointAddressEventsShouldUpdateServicePointSearchEntities(): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createPublishedServicePointForTwoStores();
        $idServicePoint = $servicePointTransfer->getIdServicePointOrFail();
        $servicePointAddressTransfer = $this->tester->createServicePointAddressTransferWithRelations([
            ServicePointAddressTransfer::SERVICE_POINT => $servicePointTransfer,
        ]);

        $servicePointAddressTransfer = $this->tester->haveServicePointAddress($servicePointAddressTransfer->toArray());

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

        $this->tester->assertServicePointAddressData($servicePointAddressTransfer, $data);
        $this->tester->assertServicePointAddressSearchData($servicePointAddressTransfer, $data[ServicePointIndexMap::SEARCH_RESULT_DATA]);
        $this->tester->assertServicePointAddressSearchData($servicePointAddressTransfer, $structuredData);
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
