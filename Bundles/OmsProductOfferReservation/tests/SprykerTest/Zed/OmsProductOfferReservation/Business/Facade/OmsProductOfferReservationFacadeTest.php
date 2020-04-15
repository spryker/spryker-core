<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsProductOfferReservation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OmsProductOfferReservation
 * @group Business
 * @group Facade
 * @group Facade
 * @group OmsProductOfferReservationFacadeTest
 * Add your own group annotations below this line
 */
class OmsProductOfferReservationFacadeTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\OmsProductOfferReservation\OmsProductOfferReservationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuantitySuccess(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveOmsProductOfferReservation([
            OmsProductOfferReservationTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            OmsProductOfferReservationTransfer::ID_STORE => $storeTransfer->getIdStore(),
            OmsProductOfferReservationTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdStore($storeTransfer->getIdStore());

        // Act
        $reservationResponseTransfer = $this->tester->getFacade()->getQuantity($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
        $this->assertSame(5, $reservationResponseTransfer->getReservationQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testGetQuantityForNotReservedProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer = $this->tester->haveStore();

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdStore($storeTransfer->getIdStore());

        // Act
        $reservationResponseTransfer = $this->tester->getFacade()->getQuantity($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
        $this->assertTrue($reservationResponseTransfer->getReservationQuantity()->isZero());
    }

    /**
     * @return void
     */
    public function testGetQuantityWithWrongStore(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer = $this->tester->haveStore();
        $wrongStoreTransfer = $this->tester->haveStore();
        $this->tester->haveOmsProductOfferReservation([
            OmsProductOfferReservationTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            OmsProductOfferReservationTransfer::ID_STORE => $storeTransfer->getIdStore(),
            OmsProductOfferReservationTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdStore($wrongStoreTransfer->getIdStore());

        // Act
        $reservationResponseTransfer = $this->tester->getFacade()->getQuantity($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
        $this->assertTrue($reservationResponseTransfer->getReservationQuantity()->isZero());
    }

    /**
     * @return void
     */
    public function testGetAggregatedReservations(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quantity = 2;
        $itemsCount = 5;

        $productOfferTransfer = $this->tester->haveProductOffer();

        $salesOrderEntity = $this->tester->haveSalesOrderWithItems(
            $productOfferTransfer,
            $quantity,
            $itemsCount,
            $storeTransfer
        );

        // Act
        $salesAggregationTransfers = $this->tester->getFacade()->getAggregatedReservations(
            (new ReservationRequestTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setReservedStates(
                    $this->tester->getOmsStateCollectionTransfer($salesOrderEntity)
                )
                ->setStore($storeTransfer)
        );

        // Assert
        $this->assertGreaterThan(1, $salesAggregationTransfers);
        $this->assertContainsOnlyInstancesOf(SalesOrderItemStateAggregationTransfer::class, $salesAggregationTransfers);

        // SUM(amount)
        $this->assertSame(
            $this->tester->sumSalesAggregationTransfers($salesAggregationTransfers)->toString(),
            (new Decimal($quantity * $itemsCount))->toString()
        );
    }
}
