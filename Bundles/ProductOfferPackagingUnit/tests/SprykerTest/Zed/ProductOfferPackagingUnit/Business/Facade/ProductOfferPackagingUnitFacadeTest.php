<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferPackagingUnit\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferPackagingUnit
 * @group Business
 * @group Facade
 * @group Facade
 * @group ProductOfferPackagingUnitFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferPackagingUnitFacadeTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductOfferPackagingUnit\ProductOfferPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetAggregatedReservations(): void
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
            $productOfferTransfer,
        ] = $this->tester->haveProductOfferPackagingUnitWithSalesOrderItems(
            $quantityInPackagingUnit,
            $amountForPackagingUnit,
            $itemsCountInPackagingUnit
        );

        $this->tester->haveSalesOrderWithItems(
            $productOfferTransfer->getProductOfferReference(),
            $itemsCount,
            $quantity,
            $productOfferTransfer->getConcreteSku()
        );

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->getAggregatedReservations(
            (new ReservationRequestTransfer())
                ->setItem(
                    (new ItemTransfer())
                        ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                        ->setSku($productOfferTransfer->getConcreteSku())
                )
                ->setReservedStates($stateCollectionTransfer)
                ->setStore($storeTransfer)
        );

        // Assert
        $this->assertCount(2, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(amount in PU) + SUM(quantity)
        $this->assertEquals(
            $result->toString(),
            $amountForPackagingUnit->multiply($itemsCountInPackagingUnit)->add($quantity * $itemsCount)->toString()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[] $salesAggregationTransfers
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
