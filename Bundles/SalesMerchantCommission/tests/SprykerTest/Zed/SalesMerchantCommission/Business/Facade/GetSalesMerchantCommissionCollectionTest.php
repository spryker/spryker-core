<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionConditionsTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group Facade
 * @group GetSalesMerchantCommissionCollectionTest
 * Add your own group annotations below this line
 */
class GetSalesMerchantCommissionCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester
     */
    protected SalesMerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesMerchantCommissionDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldRetrieveSalesMerchantCommissionsFilteredByIdSalesOrderItem(): void
    {
        // Arrange
        $persistedSalesMerchantCommissions = $this->createDummySalesMerchantCommissions();
        $salesMerchantCommissionConditionsTransfer = (new SalesMerchantCommissionConditionsTransfer())
            ->addIdSalesOrderItem(end($persistedSalesMerchantCommissions)->getIdSalesOrderItem());

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setSalesMerchantCommissionConditions($salesMerchantCommissionConditionsTransfer);

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        // Assert
        $salesMerchantCommissionTransfers = $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions();

        $this->assertCount(1, $salesMerchantCommissionTransfers);
        $this->assertSalesMerchantCommission(
            end($persistedSalesMerchantCommissions),
            $salesMerchantCommissionTransfers->getIterator()->current(),
        );
    }

    /**
     * @return void
     */
    public function testShouldRetrieveSeveralSalesMerchantCommissionsFilteredByIdSalesOrderItem(): void
    {
        // Arrange
        $persistedSalesMerchantCommissions = $this->createDummySalesMerchantCommissions();
        $salesMerchantCommissionConditionsTransfer = (new SalesMerchantCommissionConditionsTransfer())
            ->addIdSalesOrderItem(reset($persistedSalesMerchantCommissions)->getIdSalesOrderItem());

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setSalesMerchantCommissionConditions($salesMerchantCommissionConditionsTransfer);

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testShouldRetrieveSeveralSalesMerchantCommissionsFilteredByIdSalesOrder(): void
    {
        // Arrange
        $persistedSalesMerchantCommissions = $this->createDummySalesMerchantCommissions();
        $salesMerchantCommissionConditionsTransfer = (new SalesMerchantCommissionConditionsTransfer())
            ->addIdSalesOrder(reset($persistedSalesMerchantCommissions)->getIdSalesOrder());

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setSalesMerchantCommissionConditions($salesMerchantCommissionConditionsTransfer);

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollection(): void
    {
        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection(new SalesMerchantCommissionCriteriaTransfer());

        // Assert
        $this->assertCount(0, $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testReturnsSalesMerchantCommissionsByPagination(): void
    {
        // Arrange
        $this->createDummySalesMerchantCommissions();

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage(2);

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setPagination($paginationTransfer)
            ->setSalesMerchantCommissionConditions((new SalesMerchantCommissionConditionsTransfer()));

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions());
        $this->assertNotNull($salesMerchantCommissionCollectionTransfer->getPagination());

        $paginationTransfer = $salesMerchantCommissionCollectionTransfer->getPaginationOrFail();

        $this->assertSame(1, $paginationTransfer->getPageOrFail());
        $this->assertSame(2, $paginationTransfer->getMaxPerPageOrFail());
        $this->assertSame(3, $paginationTransfer->getNbResultsOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstIndexOrFail());
        $this->assertSame(2, $paginationTransfer->getLastIndexOrFail());
        $this->assertSame(1, $paginationTransfer->getFirstPage());
        $this->assertSame(2, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(2, $paginationTransfer->getLastPageOrFail());
        $this->assertSame(2, $paginationTransfer->getNextPageOrFail());
        $this->assertSame(1, $paginationTransfer->getPreviousPageOrFail());
    }

    /**
     * @return void
     */
    public function testReturnsSalesMerchantCommissionsSortedByFieldDesc(): void
    {
        // Arrange
        $this->createDummySalesMerchantCommissions();

        $sortTransfer = (new SortTransfer())
            ->setField(SalesMerchantCommissionTransfer::NAME)
            ->setIsAscending(false);

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setSalesMerchantCommissionConditions((new SalesMerchantCommissionConditionsTransfer()));

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        $salesMerchantCommissionTransfers = $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions();

        // Assert
        $this->assertCount(3, $salesMerchantCommissionTransfers);
        $this->assertSame('cab', $salesMerchantCommissionTransfers->getIterator()->offsetGet(0)->getName());
        $this->assertSame('bac', $salesMerchantCommissionTransfers->getIterator()->offsetGet(1)->getName());
        $this->assertSame('abc', $salesMerchantCommissionTransfers->getIterator()->offsetGet(2)->getName());
    }

    /**
     * @return void
     */
    public function testReturnsSalesMerchantCommissionsSortedByFieldAsc(): void
    {
        // Arrange
        $this->createDummySalesMerchantCommissions();

        $sortTransfer = (new SortTransfer())
            ->setField(SalesMerchantCommissionTransfer::NAME)
            ->setIsAscending(true);

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setSalesMerchantCommissionConditions((new SalesMerchantCommissionConditionsTransfer()));

        // Act
        $salesMerchantCommissionCollectionTransfer = $this->tester
            ->getFacade()
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);

        $salesMerchantCommissionTransfers = $salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions();

        // Assert
        $this->assertCount(3, $salesMerchantCommissionTransfers);
        $this->assertSame('abc', $salesMerchantCommissionTransfers->getIterator()->offsetGet(0)->getName());
        $this->assertSame('bac', $salesMerchantCommissionTransfers->getIterator()->offsetGet(1)->getName());
        $this->assertSame('cab', $salesMerchantCommissionTransfers->getIterator()->offsetGet(2)->getName());
    }

    /**
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    protected function createDummySalesMerchantCommissions(): array
    {
        $salesMerchantCommissions = [];
        $saveOrderTransfer1 = $this->tester->createOrderWithItem();
        $saveOrderTransfer2 = $this->tester->createOrderWithItem();

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::NAME => 'abc',
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $saveOrderTransfer1->getOrderItems()->offsetGet(0)->getIdSalesOrderItem(),
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::NAME => 'cab',
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $saveOrderTransfer1->getOrderItems()->offsetGet(0)->getIdSalesOrderItem(),
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::NAME => 'bac',
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $saveOrderTransfer2->getOrderItems()->offsetGet(0)->getIdSalesOrderItem(),
        ]);

        return $salesMerchantCommissions;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $persistedCommission
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionTransfer $commission
     *
     * @return void
     */
    protected function assertSalesMerchantCommission(
        SalesMerchantCommissionTransfer $persistedCommission,
        SalesMerchantCommissionTransfer $commission
    ): void {
        $this->assertNotEmpty($commission->getIdSalesMerchantCommission());

        $this->assertSame($persistedCommission->getIdSalesOrder(), $commission->getIdSalesOrder());
        $this->assertSame($persistedCommission->getIdSalesOrderItem(), $commission->getIdSalesOrderItem());
        $this->assertSame($persistedCommission->getName(), $commission->getName());
        $this->assertSame($persistedCommission->getAmount(), $commission->getAmount());
        $this->assertSame($persistedCommission->getRefundedAmount(), $commission->getRefundedAmount());

        $this->assertNotEmpty($commission->getCreatedAt());
        $this->assertNotEmpty($commission->getUpdatedAt());
    }
}
