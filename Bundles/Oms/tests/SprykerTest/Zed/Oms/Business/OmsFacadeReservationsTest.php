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
use Generated\Shared\Transfer\StoreTransfer;
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
     * @dataProvider importReservationShouldHaveAmountInReservationTotalsDataProvider
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $availabilityReservationRequestTransfer
     * @param float $expectedResult
     *
     * @return void
     */
    public function testImportReservationShouldHaveAmountInReservationTotals(
        StoreTransfer $storeTransfer,
        OmsAvailabilityReservationRequestTransfer $availabilityReservationRequestTransfer,
        float $expectedResult
    ) {
        $omsFacade = $this->createOmsFacade();

        $omsFacade->importReservation($availabilityReservationRequestTransfer);

       //other store
        $storeTransfer = $storeTransfer->setName('DE');
        $reservedAmount = $omsFacade->getOmsReservedProductQuantityForSku(123, $storeTransfer);

        $this->assertSame($expectedResult, $reservedAmount);
    }

    /**
     * @return array
     */
    public function importReservationShouldHaveAmountInReservationTotalsDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForImportReservationShouldHaveAmountInReservationTotals(1),
            'float stock' => $this->getDataForImportReservationShouldHaveAmountInReservationTotals(0.1),
        ];
    }

    /**
     * @param int|float $quantity
     *
     * @return array
     */
    protected function getDataForImportReservationShouldHaveAmountInReservationTotals($quantity): array
    {
        $storeTransfer = $this->createStoreTransfer()->setName('AT');
        $availabilityReservationRequestTransfer = (new OmsAvailabilityReservationRequestBuilder([
            OmsAvailabilityReservationRequestTransfer::SKU => 123,
            OmsAvailabilityReservationRequestTransfer::VERSION => 1,
            OmsAvailabilityReservationRequestTransfer::ORIGIN_STORE => $storeTransfer,
            OmsAvailabilityReservationRequestTransfer::RESERVATION_AMOUNT => $quantity,
        ]))->build();

        return [$storeTransfer, $availabilityReservationRequestTransfer, $quantity];
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
     * @dataProvider getReservationsFromOtherStoresShouldReturnReservationsDataProvider
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $availabilityReservationRequestTransfer
     * @param float $expectedResult
     *
     * @return void
     */
    public function testGetReservationsFromOtherStoresShouldReturnReservations(
        StoreTransfer $storeTransfer,
        OmsAvailabilityReservationRequestTransfer $availabilityReservationRequestTransfer,
        float $expectedResult
    ): void {
        $omsFacade = $this->createOmsFacade();

        $omsFacade->importReservation($availabilityReservationRequestTransfer);

        $storeTransfer = $storeTransfer->setName('DE');
        $reserved = $omsFacade->getReservationsFromOtherStores(123, $storeTransfer);

        $this->assertSame($expectedResult, $reserved);
    }

    /**
     * @return array
     */
    public function getReservationsFromOtherStoresShouldReturnReservationsDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForGetReservationsFromOtherStoresShouldReturnReservations(1),
            'float stock' => $this->getDataForGetReservationsFromOtherStoresShouldReturnReservations(0.1),
        ];
    }

    /**
     * @param int|float $quantity
     *
     * @return array
     */
    protected function getDataForGetReservationsFromOtherStoresShouldReturnReservations($quantity): array
    {
        $storeTransfer = $this->createStoreTransfer()->setName('AT');
        $availabilityReservationRequestTransfer = (new OmsAvailabilityReservationRequestBuilder([
            OmsAvailabilityReservationRequestTransfer::SKU => 123,
            OmsAvailabilityReservationRequestTransfer::VERSION => 1,
            OmsAvailabilityReservationRequestTransfer::ORIGIN_STORE => $storeTransfer,
            OmsAvailabilityReservationRequestTransfer::RESERVATION_AMOUNT => $quantity,
        ]))->build();

        return [$storeTransfer, $availabilityReservationRequestTransfer, (float)$quantity];
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
