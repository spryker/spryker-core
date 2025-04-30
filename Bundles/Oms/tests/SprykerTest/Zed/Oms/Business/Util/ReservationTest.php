<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Util;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use ReflectionProperty;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Oms\Business\Reader\ReservationReaderInterface;
use Spryker\Zed\Oms\Business\Util\Reservation;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Util
 * @group ReservationTest
 * Add your own group annotations below this line
 */
class ReservationTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateReservationQuantityGetsAllStores(): void
    {
        // Arrange
        $reservationReaderMock = $this->createReservationReaderMock();
        $storeTransfers = $this->tester->getLocator()->store()->facade()->getAllStores();

        // Assert
        $reservationReaderMock->expects($this->once())
            ->method('sumReservedProductQuantitiesForSku')
            ->willReturn(new Decimal(1));

        // Act
        $reservation = new Reservation(
            $reservationReaderMock,
            [],
            $this->createStoreFacadeMock($storeTransfers),
            $this->createOmsRepositoryMock(count($storeTransfers)),
            $this->createOmsEntityManagerMock(count($storeTransfers)),
            [],
            [],
        );
        $reservation->updateReservationQuantity($this->tester::FAKE_SKU);
    }

    /**
     * @return void
     */
    public function testUpdateReservationGetsAllStores(): void
    {
        // Arrange
        $reflectionProperty = new ReflectionProperty(Reservation::class, 'allStoreTransfersCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, []);
        $reservationRequestTransfer = new ReservationRequestTransfer();
        $reservationReaderMock = $this->createReservationReaderMock();
        $storeTransfers = $this->tester->getLocator()->store()->facade()->getAllStores();

        // Assert
        $reservationReaderMock->expects($this->exactly(count($storeTransfers)))
            ->method('sumReservedProductQuantities')
            ->willReturn(new Decimal(1));

        // Act
        $reservation = new Reservation(
            $reservationReaderMock,
            [],
            $this->createStoreFacadeMock($storeTransfers),
            $this->createOmsRepositoryMock(count($storeTransfers)),
            $this->createOmsEntityManagerMock(count($storeTransfers)),
            [],
            [],
        );
        $reservation->updateReservation($reservationRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Reader\ReservationReaderInterface
     */
    protected function createReservationReaderMock(): ReservationReaderInterface
    {
        return $this->createMock(ReservationReaderInterface::class);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected function createStoreFacadeMock(array $storeTransfers): OmsToStoreFacadeInterface
    {
        $storeFacadeMock = $this->createMock(OmsToStoreFacadeInterface::class);
        $storeFacadeMock->expects($this->once())
            ->method('getAllStores')
            ->willReturn($storeTransfers);

        return $storeFacadeMock;
    }

    /**
     * @param int $storesCount
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected function createOmsRepositoryMock(int $storesCount): OmsRepositoryInterface
    {
        return $this->createMock(OmsRepositoryInterface::class);
    }

    /**
     * @param int $storesCount
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface
     */
    protected function createOmsEntityManagerMock(int $storesCount): OmsEntityManagerInterface
    {
        $omsEntityManagerMock = $this->createMock(OmsEntityManagerInterface::class);
        $omsEntityManagerMock->expects($this->exactly($storesCount))
            ->method('saveReservation');

        return $omsEntityManagerMock;
    }
}
