<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\OmsConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Facade
 * @group OmsFacadeTest
 * Add your own group annotations below this line
 */
class OmsFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testClearLocksShouldEmptyDatabaseFromExpiredLocks()
    {
        $identifier = '1-2-3';

        $omsFacade = $this->createOmsFacade();

        $omsLockEntity = new SpyOmsStateMachineLock();
        $omsLockEntity->setIdentifier($identifier);
        $omsLockEntity->setExpires(new DateTime('Yesterday'));
        $omsLockEntity->save();

        $omsFacade->clearLocks();

        $numberOfItems = SpyOmsStateMachineLockQuery::create()->filterByIdentifier($identifier)->count();

        $this->assertEquals(0, $numberOfItems);
    }

    /**
     * @return void
     */
    public function testOrderMatrixCreation()
    {
        $omsFacade = $this->createOmsFacade();

        $matrix = $omsFacade->getOrderItemMatrix();

        $this->assertNotEmpty($matrix);
        $this->assertSame('', $matrix[0]['COL_STATE']);
    }

    /**
     * @return void
     */
    public function testIsOrderFlaggedExcludeFromCustomerShouldReturnTrueWhenAllStatesHaveFlag()
    {
        $testStateMachineProcessName = 'Test01';

        $omsFacade = $this->createOmsFacadeWithTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();

        $salesOrderEntity = SpySalesOrderQuery::create()->filterByIdSalesOrder($idSalesOrder)->findOne();

        $omsFacade->triggerEvent('authorization-failed', $salesOrderEntity->getItems(), []);

        $isOrderExcluded = $omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder);

        $this->assertTrue($isOrderExcluded);
    }

    /**
     * @return void
     */
    public function testIsOrderFlaggedExcludeFromCustomerShouldReturnFalseWhenAnyOfStatesMissingFlag()
    {
        $testStateMachineProcessName = 'Test01';

        $omsFacade = $this->createOmsFacadeWithTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();

        $isOrderExcluded = $omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder);

        $this->assertFalse($isOrderExcluded);
    }

    /**
     * @return void
     */
    public function testReservedItemsByNonExistentSku()
    {
        $omsFacade = $this->createOmsFacade();
        $items = $omsFacade->getReservedOrderItemsForSku('non-existent-sku');

        $this->assertSame(0, $items->count());
    }

    /**
     * @return void
     */
    public function testGetReservedStateNames(): void
    {
        $expected = [
            'new',
            'payment pending',
            'paid',
            'exported',
            'shipped',
        ];

        // Action
        $stateNames = $this->createOmsFacade()->getReservedStateNames();

        // Assert
        $this->assertSame($expected, $stateNames);
    }

    /**
     * @return void
     */
    public function testSaveReservation(): void
    {
        $omsFacade = $this->createOmsFacade();
        $storeTransfer = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $productSku = 'xxx';
        $reservationQuantity = 10;

        // Action
        $this->createOmsFacade()->saveReservation($productSku, $storeTransfer, $reservationQuantity);

        // Assert
        $this->assertEquals(
            $reservationQuantity,
            $this->createOmsFacade()->getOmsReservedProductQuantityForSku($productSku, $storeTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacade()
    {
        $omsBusinessFactory = new OmsBusinessFactory();
        $omsConfig = new OmsConfig();
        $omsBusinessFactory->setConfig($omsConfig);

        $omsFacade = new OmsFacade();
        $omsFacade->setFactory($omsBusinessFactory);

        return $omsFacade;
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacadeWithTestStateMachine(array $activeProcesses = [], $xmlFolder = null)
    {
        $this->tester->configureTestStateMachine($activeProcesses, $xmlFolder);

        return new OmsFacade();
    }
}
