<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Reader
 * @group ReservationReaderTest
 * Add your own group annotations below this line
 */
class ReservationReaderTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';
    protected const NOT_RESERVED_SUBPROCESS_ITEM_STATE = 'awaiting approval';
    protected const RESERVED_SUBPROCESS_ITEM_STATE = 'payment preparations';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->resetReservedStatesCache();
        $this->tester->resetReservedStateProcessNamesCache();
        $this->tester->configureTestStateMachine(['Test06']);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->tester->resetReservedStatesCache();
        $this->tester->resetReservedStateProcessNamesCache();
    }

    /**
     * @return void
     */
    public function testSumReservedProductQuantitiesShouldSumAllItemsInReservedStateIncludedSubProcesses(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->createOrderWithOrderItemsInStateAndProcessForStore(
            static::STORE_NAME_DE,
            static::NOT_RESERVED_SUBPROCESS_ITEM_STATE,
            'Test06',
            6
        );

        $totalQuantity = 0;
        $itemSku = $salesOrderEntity->getItems()->getFirst()->getSku();

        foreach ($salesOrderEntity->getItems() as $orderItem) {
            $this->applyOrderItemSkuAndQuantityForQuantityCheck($orderItem, $itemSku);
            $totalQuantity += $orderItem->getQuantity();
        }

        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($itemSku);

        $reservationReader = (new OmsBusinessFactory())->createReservationReader();
        $reservedSubprocessItemStateEntity = $this->tester->haveOmsOrderItemStateEntity(static::RESERVED_SUBPROCESS_ITEM_STATE);

        //Act
        $sumReservedProductQuantitiesBefore = $reservationReader->sumReservedProductQuantities(
            $reservationRequestTransfer
        );

        foreach ($salesOrderEntity->getItems() as $orderItem) {
            $orderItem->setState($reservedSubprocessItemStateEntity)->save();
        }

        $sumReservedProductQuantitiesAfter = $reservationReader->sumReservedProductQuantities(
            $reservationRequestTransfer
        );

        // Assert
        $this->assertTrue(
            $sumReservedProductQuantitiesBefore->equals(0),
            'Expected reserved product quantity to be 0 for non-reserved state of subprocess.'
        );

        $this->assertTrue(
            $sumReservedProductQuantitiesAfter->equals($totalQuantity),
            'Expected reserved product quantity to be 50 for reserved state of subprocess.'
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param string $itemSku
     *
     * @return void
     */
    protected function applyOrderItemSkuAndQuantityForQuantityCheck(SpySalesOrderItem $orderItem, string $itemSku): void
    {
        $itemQuantity = rand(1, 10);

        $orderItem->setSku($itemSku)
            ->setQuantity($itemQuantity)
            ->save();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }
}
