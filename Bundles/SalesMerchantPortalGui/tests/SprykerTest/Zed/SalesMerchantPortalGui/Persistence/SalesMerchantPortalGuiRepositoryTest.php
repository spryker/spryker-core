<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantPortalGui\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantPortalGui
 * @group Persistence
 * @group SalesMerchantPortalGuiRepositoryTest
 * Add your own group annotations below this line
 */
class SalesMerchantPortalGuiRepositoryTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiPersistenceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\MerchantTransfer
     */
    protected $merchantTransfer;

    /**
     * @var \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected $merchantOrderTransfer1;

    /**
     * @var \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected $merchantOrderTransfer2;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface
     */
    protected $salesMerchantPortalGuiRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->salesMerchantPortalGuiRepository = new SalesMerchantPortalGuiRepository();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);

        $this->merchantTransfer = $this->tester->haveMerchant();

        $this->merchantOrderTransfer1 = $this->createMerchantOrder($this->merchantTransfer);
        $this->merchantOrderTransfer2 = $this->createMerchantOrder($this->merchantTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantOrderTableDataReturnsCorrectMerchantOrderData(): void
    {
        // Arrange
        $merchantOrderTableCriteriaTransfer = (new MerchantOrderTableCriteriaTransfer())
            ->setIdMerchant($this->merchantTransfer->getIdMerchant())
            ->setPage(1)
            ->setPageSize(10);

        // Act
        $merchantOrderCollectionTransfer = $this->salesMerchantPortalGuiRepository->getMerchantOrderTableData($merchantOrderTableCriteriaTransfer);

        // Assert
        $this->assertCount(2, $merchantOrderCollectionTransfer->getMerchantOrders());
        $this->assertEquals($this->merchantTransfer->getMerchantReference(), $merchantOrderCollectionTransfer->getMerchantOrders()->offsetGet(0)->getMerchantReference());
        $this->assertEquals($this->merchantTransfer->getMerchantReference(), $merchantOrderCollectionTransfer->getMerchantOrders()->offsetGet(1)->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testGetOrderTotalsPerStoreReturnsCorrectOrderTotalsData(): void
    {
        // Act
        $merchantOrderCountsTransfer = $this->salesMerchantPortalGuiRepository->getMerchantOrderCounts($this->merchantTransfer->getIdMerchant());

        // Assert
        $this->assertEquals(2, $merchantOrderCountsTransfer->getTotal());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function createMerchantOrder(MerchantTransfer $merchantTransfer): MerchantOrderTransfer
    {
        $orderTransfer = $this->tester->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantOrderTransfer::ID_ORDER => $orderTransfer->getIdSalesOrder(),
        ]);

        foreach ($orderTransfer->getOrderItems() as $orderItemTransfer) {
            $this->tester->haveMerchantOrderItem([
                MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
                MerchantOrderItemTransfer::ID_ORDER_ITEM => $orderItemTransfer->getIdSalesOrderItem(),
            ]);
        }

        $this->tester->haveMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder());

        return $merchantOrderTransfer;
    }
}
