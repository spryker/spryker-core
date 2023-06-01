<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServiceTransfer;
use SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Business
 * @group ServicePointSearchFacade
 * @group WriteCollectionByServiceEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByServiceEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_SERVICE_FK_SERVICE_POINT = 'spy_service.fk_service_point';

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
     * @dataProvider getWriteCollectionByServiceEventsDataProvider
     *
     * @param bool $isActive
     *
     * @return void
     */
    public function testWriteCollectionByServiceEvents(bool $isActive): void
    {
        // Arrange
        $servicePointTransfer = $this->tester->createPublishedServicePointForTwoStores();
        $idServicePoint = $servicePointTransfer->getIdServicePoint();

        $serviceTransfer = $this->tester->haveService([
            ServiceTransfer::IS_ACTIVE => $isActive,
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
        ]);

        $serviceTypes = [];
        if ($isActive) {
            $serviceTypes[] = $serviceTransfer->getServiceType()->getKey();
        }

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromForeignKeys(
            static::COL_SERVICE_FK_SERVICE_POINT,
            [$idServicePoint],
        );

        // Act
        $this->tester->getFacade()->writeCollectionByServiceEvents($eventEntityTransfers);

        // Assert
        $publishedServicePointSearchEntities = $this->tester->getServicePointSearchEntitiesByServicePointIds([$idServicePoint], [static::TEST_STORE_DE]);
        $this->assertCount(1, $publishedServicePointSearchEntities);

        /** @var array<string, mixed> $data */
        $data = $publishedServicePointSearchEntities->getIterator()->current()->getData();
        $this->tester->assertServiceData($serviceTypes, $data);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByServiceEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Act
        $this->tester->getFacade()->writeCollectionByServiceEvents([]);

        // Assert
        $this->assertSame(0, $this->tester->getServicePointSearchEntitiesByServicePointIds()->count());
    }

    /**
     * @return array<string, list<bool>>
     */
    protected function getWriteCollectionByServiceEventsDataProvider(): array
    {
        return [
            'Should not add inactive service type to service point search entities' => [false],
            'Should add active service type to service point search entities' => [true],
        ];
    }
}
