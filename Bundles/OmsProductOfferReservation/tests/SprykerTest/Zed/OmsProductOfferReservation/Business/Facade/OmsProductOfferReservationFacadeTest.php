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
    public function testSaveReservationSuccess(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setProductOfferReference('test')
            ->setStore($storeTransfer)
            ->setReservationQuantity(5);

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($reservationRequestTransfer->getProductOfferReference())
            ->setIdStore($reservationRequestTransfer->getStore()->getIdStore());

        // Act
        $this->tester->getFacade()->saveReservation($reservationRequestTransfer);
        $reservationResponseTransfer = $this->tester->getFacade()->getQuantity($omsProductOfferReservationCriteriaTransfer);

        // Assert
        // Assert
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
        $this->assertSame(5, $reservationResponseTransfer->getReservationQuantity()->toInt());
    }
}
