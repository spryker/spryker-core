<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsProductOfferReservation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
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
    public function testGetQuantityForProductOfferSuccess(): void
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
        $this->assertSame(5, $reservationResponseTransfer->getReservationQuantity()->toInt());
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
    }

    /**
     * @return void
     */
    public function testGetQuantityForProductOfferForNotReservedProductOffer()
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
        $this->assertTrue($reservationResponseTransfer->getReservationQuantity()->isZero());
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
    }

    /**
     * @return void
     */
    public function testGetQuantityForProductOfferWithWrongStore()
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
        $this->assertTrue($reservationResponseTransfer->getReservationQuantity()->isZero());
        $this->assertInstanceOf(Decimal::class, $reservationResponseTransfer->getReservationQuantity());
    }
}
