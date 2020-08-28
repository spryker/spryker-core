<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferPackagingUnit\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
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

        $leadProductConcreteTransfer = $this->tester->haveProduct();
        $packagingUnitProductConcreteTransfer = $this->tester->haveProduct();
        $packagingUnitProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => 'packagingUnit']);
        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $leadProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $packagingUnitProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $packagingUnitProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => 1,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([ProductOfferTransfer::CONCRETE_SKU => $leadProductConcreteTransfer->getSku()]);

        $salesOrderEntity = $this->tester->haveSalesOrderWithItems(
            $productOfferTransfer->getProductOfferReference(),
            $itemsCountInPackagingUnit,
            $quantityInPackagingUnit,
            $storeTransfer,
            $packagingUnitProductConcreteTransfer->getSku(),
            $amountForPackagingUnit,
            $leadProductConcreteTransfer->getSku()
        );
        $this->tester->haveSalesOrderWithItems(
            $productOfferTransfer->getProductOfferReference(),
            $itemsCount,
            $quantity,
            $storeTransfer,
            $productOfferTransfer->getConcreteSku()
        );

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->getAggregatedReservations(
            (new ReservationRequestTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setSku($productOfferTransfer->getConcreteSku())
                ->setReservedStates(
                    $this->tester->getOmsStateCollectionTransfer($salesOrderEntity)
                )
                ->setStore($storeTransfer)
        );

        // Assert
        $this->assertCount(2, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);
        $result = $this->tester->sumSalesAggregationTransfers($salesAggregationTransfers);
        // SUM(amount in PU) + SUM(quantity)
        $this->assertEquals(
            $result->toString(),
            $amountForPackagingUnit->multiply($itemsCountInPackagingUnit)->add($quantity * $itemsCount)->toString()
        );
    }
}
