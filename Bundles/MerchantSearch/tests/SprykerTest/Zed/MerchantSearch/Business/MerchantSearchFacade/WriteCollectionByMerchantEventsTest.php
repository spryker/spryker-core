<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSearch\Business\MerchantSearchFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSearch
 * @group Business
 * @group MerchantSearchFacade
 * @group WriteCollectionByMerchantEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByMerchantEventsTest extends Unit
{
    protected const MERCHANT_COUNT = 3;

    /**
     * @var \SprykerTest\Zed\MerchantSearch\MerchantSearchBusinessTester
     */
    protected $tester;

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
    public function testWriteActiveApprovedCollectionByMerchantEventsWritesRecords(): void
    {
        // Arrange
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($merchantIds);

        // Act
        $this->tester->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);
        $merchantEntities = $this->tester->getMerchantEntitiesByMerchantIds($merchantIds);

        // Assert
        $this->assertSame(
            count($merchantIds),
            $merchantEntities->count()
        );
    }

    /**
     * @return void
     */
    public function testWriteDefaultCollectionByMerchantEventsWritesRecords(): void
    {
        // Arrange
        $merchantTransfers = [];
        for ($i = 0; $i <= static::MERCHANT_COUNT; $i++) {
            $merchantTransfers[] = $this->tester->haveMerchant();
        }
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($merchantIds);

        // Act
        $this->tester->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);
        $merchantEntities = $this->tester->getMerchantEntitiesByMerchantIds($merchantIds);

        // Assert
        $this->assertSame(
            0,
            $merchantEntities->count()
        );
    }
}
