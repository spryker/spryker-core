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
 * @group GetMerchantSynchronizationDataTransfersByIdsTest
 * Add your own group annotations below this line
 */
class GetMerchantSynchronizationDataTransfersByIdsTest extends Unit
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
    public function testGetMerchantSynchronizationDataTransfersByIdsWorksWithIds(): void
    {
        // Act
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        $merchantEntities = $this->tester->getMerchantEntitiesByMerchantIds($merchantIds);
        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByMerchantIds(
            new FilterTransfer(),
            $merchantIds
        );

        // Assert
        $this->assertSame(
            count($merchantEntities),
            count($synchronizationDataTransfers)
        );
    }

    /**
     * @return void
     */
    public function testGetMerchantSynchronizationDataTransfersByIdsWorksWithFilter(): void
    {
        // Act
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByMerchantIds(
            (new FilterTransfer())->setOffset(1)->setLimit(1)
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetMerchantSynchronizationDataTransfersByIdsWorksWithFilterAndIds(): void
    {
        // Act
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByMerchantIds(
            (new FilterTransfer())->setOffset(0)->setLimit(1),
            [$merchantIds[0]]
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }
}
