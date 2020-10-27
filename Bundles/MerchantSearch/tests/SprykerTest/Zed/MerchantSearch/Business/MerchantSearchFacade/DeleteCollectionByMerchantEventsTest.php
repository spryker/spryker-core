<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSearch\Business\MerchantSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSearch
 * @group Business
 * @group MerchantSearchFacade
 * @group DeleteCollectionByMerchantEventsTest
 * Add your own group annotations below this line
 */
class DeleteCollectionByMerchantEventsTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     */
    protected const MERCHANT_STATUS_DENIED = 'denied';

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
    public function testDeleteMerchantSearchByChangingMerchantStatusAndMerchantEventsDeletesRecords(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createActiveMerchants()[0];
        $merchantId = $merchantTransfer->getIdMerchant();

        // Act
        $merchantTransfer->setStatus(static::MERCHANT_STATUS_DENIED);
        $this->tester->updateMerchant($merchantTransfer);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds([$merchantId]);
        $this->tester->getFacade()->deleteCollectionByMerchantEvents($eventEntityTransfers);

        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByMerchantIds(
            (new FilterTransfer())->setOffset(0)->setLimit(1),
            [$merchantId]
        );

        // Assert
        $this->assertCount(0, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantSearchByChangingActiveStateAndMerchantEventsDeletesRecords(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createActiveMerchants()[0];
        $merchantId = $merchantTransfer->getIdMerchant();

        // Act
        $merchantTransfer->setIsActive(false);
        $this->tester->updateMerchant($merchantTransfer);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds([$merchantId]);
        $this->tester->getFacade()->deleteCollectionByMerchantEvents($eventEntityTransfers);

        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByMerchantIds(
            (new FilterTransfer())->setOffset(0)->setLimit(1),
            [$merchantId]
        );

        // Assert
        $this->assertCount(0, $synchronizationDataTransfers);
    }
}
