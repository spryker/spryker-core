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
     * @uses \SprykerTest\Zed\MerchantSearch\MerchantSearchBusinessTester::MERCHANT_COUNT
     */
    protected const MERCHANT_COUNT = 3;

    /**
     * @uses \Generated\Shared\Search\MerchantIndexMap::SEARCH_RESULT_DATA
     */
    protected const SEARCH_RESULT_DATA = 'search-result-data';

    protected const ID_MERCHANT = 'id_merchant';

    /**
     * @uses \Orm\Zed\MerchantSearch\Persistence\Map\SpyMerchantSearchTableMap::COL_FK_MERCHANT
     */
    protected const COL_FK_MERCHANT = 'spy_merchant_search.fk_merchant';

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
    public function testGetSynchronizationDataTransfersByMerchantIdsWorksWithIds(): void
    {
        // Arrange
        $merchantTransfers = $this->tester->createActiveMerchants(static::MERCHANT_COUNT);
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getSynchronizationDataTransfersByMerchantIds(
                new FilterTransfer(),
                $merchantIds
            );

        // Assert
        $this->assertCount(
            static::MERCHANT_COUNT,
            $synchronizationDataTransfers
        );
    }

    /**
     * @return void
     */
    public function testGetSynchronizationDataTransfersByMerchantIdsWorksWithFilter(): void
    {
        // Arrange
        $merchantTransfers = $this->tester->createActiveMerchants(static::MERCHANT_COUNT);
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getSynchronizationDataTransfersByMerchantIds(
                (new FilterTransfer())->setOffset(0)->setLimit(1)->setOrderBy(static::COL_FK_MERCHANT)
            );
        $synchronizationData = $synchronizationDataTransfers[0]->getData();

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $this->assertSame($merchantIds[0], $synchronizationData[static::SEARCH_RESULT_DATA][static::ID_MERCHANT]);
    }

    /**
     * @return void
     */
    public function testGetSynchronizationDataTransfersByMerchantIdsWorksWithFilterAndIds(): void
    {
        // Arrange
        $merchantTransfers = $this->tester->createActiveMerchants();
        $merchantIds = $this->tester->extractMerchantIdsFromMerchantTransfers($merchantTransfers);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getSynchronizationDataTransfersByMerchantIds(
                (new FilterTransfer())->setOffset(0)->setLimit(1),
                [$merchantIds[0]]
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }
}
