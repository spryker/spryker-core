<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\OmsAvailabilityReservationRequestBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Facade
 * @group OmsFacadeReservationsTest
 * Add your own group annotations below this line
 */
class OmsFacadeReservationsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * Import from store AT to store DE
     *
     * @return void
     */
    public function testImportReservationShouldHaveAmountInReservationTotals()
    {
        $omsFacade = $this->createOmsFacade();

       //origin store
        $storeTransfer = $this->createStoreTransfer()->setName('AT');

        $availabilityReservationRequestTransfer = (new OmsAvailabilityReservationRequestBuilder([
           OmsAvailabilityReservationRequestTransfer::SKU => 123,
           OmsAvailabilityReservationRequestTransfer::VERSION => 1,
           OmsAvailabilityReservationRequestTransfer::ORIGIN_STORE => $storeTransfer,
           OmsAvailabilityReservationRequestTransfer::RESERVATION_AMOUNT => 1,
        ]))->build();

        $omsFacade->importReservation($availabilityReservationRequestTransfer);

       //other store
        $storeTransfer = $this->createStoreTransfer()->setName('DE');
        $reservedAmount = $omsFacade->getOmsReservedProductQuantityForSku('123', $storeTransfer);

        $this->assertSame('1', $reservedAmount->toString());
    }

    /**
     * @return void
     */
    public function testExportReservationShouldExportAllUnExportedReservations()
    {
        $omsFacade = $this->createOmsFacade();

        $testStateMachineProcessName = 'Test01';

        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $salesOrderEntity = SpySalesOrderQuery::create()
            ->filterByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->findOne();

        $items = $salesOrderEntity->getItems();

        $omsFacade->triggerEvent('authorize', $items, []);
        $omsFacade->triggerEvent('pay', $items, []);

        $omsFacade->saveReservationVersion($items[0]->getSku());

        $omsFacade->exportReservation();

        $this->assertGreaterThan(0, $omsFacade->getLastExportedReservationVersion());
    }

    /**
     * Import from store AT to store DE
     *
     * @return void
     */
    public function testGetReservationsFromOtherStoresShouldReturnReservations()
    {
        $omsFacade = $this->createOmsFacade();
        $storeTransfer = $this->createStoreTransfer()->setName('AT');

        $availabilityReservationRequestTransfer = (new OmsAvailabilityReservationRequestBuilder([
            OmsAvailabilityReservationRequestTransfer::SKU => 123,
            OmsAvailabilityReservationRequestTransfer::VERSION => 1,
            OmsAvailabilityReservationRequestTransfer::ORIGIN_STORE => $storeTransfer,
            OmsAvailabilityReservationRequestTransfer::RESERVATION_AMOUNT => 1,
        ]))->build();

        $omsFacade->importReservation($availabilityReservationRequestTransfer);

        $storeTransfer = $this->createStoreTransfer()->setName('DE');
        $reserved = $omsFacade->getReservationsFromOtherStores('123', $storeTransfer);

        $this->assertSame('1', $reserved->toString());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createStoreTransfer()
    {
        return (new StoreBuilder())->build();
    }
}
