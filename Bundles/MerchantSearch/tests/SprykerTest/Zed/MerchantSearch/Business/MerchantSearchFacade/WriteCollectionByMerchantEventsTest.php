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
    public function testWriteCollectionByMerchantEventsForActiveMerchantRecords(): void
    {
        // Arrange
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($merchantIds);

        // Act
        $this->tester->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);

        // Assert
        $merchantSearchCount = $this->tester->getMerchantSearchCount($merchantIds);

        $this->assertSame(
            count($merchantIds),
            $merchantSearchCount
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByMerchantEventsReturnsNothingForInactiveMerchantRecords(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers([$merchantTransfer]);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($merchantIds);

        // Act
        $this->tester->getFacade()->writeCollectionByMerchantEvents($eventEntityTransfers);

        // Assert
        $merchantSearchCount = $this->tester->getMerchantSearchCount($merchantIds);

        $this->assertSame(0, $merchantSearchCount);
    }
}
