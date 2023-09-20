<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group AggregateProductPackagingUnitReservationTest
 * Add your own group annotations below this line
 */
class AggregateProductPackagingUnitReservationTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testAggregateProductPackagingUnitReservationAmount(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 2;
        $amount = new Decimal(2.5);
        $itemsCount = 5;
        [
            $stateCollectionTransfer,
            $leadProductConcreteTransfer,
        ] = $this->tester->haveProductPackagingUnitWithSalesOrderItems($quantity, $amount, $itemsCount);

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->aggregateProductPackagingUnitReservation(
            $leadProductConcreteTransfer->getSku(),
            $stateCollectionTransfer,
            $storeTransfer,
        );

        // Assert
        $this->assertGreaterThan(1, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(amount)
        $this->assertSame(
            $result->toString(),
            $amount->multiply($itemsCount)->toString(),
        );
    }

    /**
     * @return void
     */
    public function testAggregateProductPackagingUnitReservationAmountWithSelfLeadProduct(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 3;
        $amount = new Decimal(1.5);
        $itemsCount = 6;
        [
            $stateCollectionTransfer,
            $leadProductConcreteTransfer,
        ] = $this->tester->haveProductPackagingUnitWithSalesOrderItems($quantity, $amount, $itemsCount, true);

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->aggregateProductPackagingUnitReservation(
            $leadProductConcreteTransfer->getSku(),
            $stateCollectionTransfer,
            $storeTransfer,
        );

        // Assert
        $this->assertCount(1, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(amount)
        $this->assertSame(
            $result->toString(),
            $amount->multiply($itemsCount)->trim()->toString(),
        );
    }

    /**
     * @return void
     */
    public function testAggregateProductPackagingUnitReservationAmountWithNoPackagingUnit(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 3;
        $itemsCount = 7;
        $sku = 'NOT_PU_PRODUCT';
        $stateCollectionTransfer = $this->tester->haveSalesOrderWithItems($itemsCount, $quantity, $sku);

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->aggregateProductPackagingUnitReservation(
            $sku,
            $stateCollectionTransfer,
            $storeTransfer,
        );

        // Assert
        $this->assertCount(1, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(quantity)
        $this->assertSame(
            $result->toInt(),
            $quantity * $itemsCount,
        );
    }

    /**
     * @return void
     */
    public function testAggregateProductPackagingUnitReservationAmountWithNoPackagingUnitButAlsoPackagingUnit(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 4;
        $itemsCount = 9;
        $quantityInPackagingUnit = 6;
        $amountForPackagingUnit = new Decimal(1.2);
        $itemsCountInPackagingUnit = 3;
        [
            $stateCollectionTransfer,
            $leadProductConcreteTransfer,
        ] = $this->tester->haveProductPackagingUnitWithSalesOrderItems($quantityInPackagingUnit, $amountForPackagingUnit, $itemsCountInPackagingUnit);
        $this->tester->haveSalesOrderWithItems($itemsCount, $quantity, $leadProductConcreteTransfer->getSku());

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->aggregateProductPackagingUnitReservation(
            $leadProductConcreteTransfer->getSku(),
            $stateCollectionTransfer,
            $storeTransfer,
        );

        // Assert
        $this->assertCount(2, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(amount in PU) + SUM(quantity)
        $this->assertSame(
            $result->toString(),
            $amountForPackagingUnit->multiply($itemsCountInPackagingUnit)->add($quantity * $itemsCount)->toString(),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer> $salesAggregationTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function sumSalesAggregationTransfers(array $salesAggregationTransfers): Decimal
    {
        return array_reduce($salesAggregationTransfers, function (Decimal $result, SalesOrderItemStateAggregationTransfer $salesAggregationTransfer) {
            return $result->add($salesAggregationTransfer->getSumAmount())->trim();
        }, new Decimal(0));
    }
}
