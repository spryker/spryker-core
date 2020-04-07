<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsProductOfferReservation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
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
 * @group OmsProductOfferReservationTest
 * Add your own group annotations below this line
 */
class OmsProductOfferReservationTest extends Unit
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
            ProductOfferTransfer::MERCHANT_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
            ReservationResponseTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStoreName($storeTransfer->getName());

        // Act
        $reservatiionResponseTransfer = $this->tester->getFacade()->getQuantityForProductOffer($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertSame(5, $reservatiionResponseTransfer->getReservationQuantity()->toInt());
        $this->assertInstanceOf(Decimal::class, $reservatiionResponseTransfer->getReservationQuantity());
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
            ->setStoreName($storeTransfer->getName());

        // Act
        $reservatiionResponseTransfer = $this->tester->getFacade()->getQuantityForProductOffer($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertTrue($reservatiionResponseTransfer->getReservationQuantity()->isZero());
        $this->assertInstanceOf(Decimal::class, $reservatiionResponseTransfer->getReservationQuantity());
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
            ProductOfferTransfer::MERCHANT_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
            ReservationResponseTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStoreName($wrongStoreTransfer->getName());

        // Act
        $reservatiionResponseTransfer = $this->tester->getFacade()->getQuantityForProductOffer($omsProductOfferReservationCriteriaTransfer);

        // Assert
        $this->assertTrue($reservatiionResponseTransfer->getReservationQuantity()->isZero());
        $this->assertInstanceOf(Decimal::class, $reservatiionResponseTransfer->getReservationQuantity());
    }
}
