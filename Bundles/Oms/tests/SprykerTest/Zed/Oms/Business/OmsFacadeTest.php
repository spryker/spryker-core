<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\OmsConfig;

/**
 * Auto-generated group annotations
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

        $checkoutResponseTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();

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

        $checkoutResponseTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();

        $isOrderExcluded = $omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder);

        $this->assertFalse($isOrderExcluded);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacade
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
     * @return \Spryker\Zed\Oms\Business\OmsFacade
     */
    protected function createOmsFacadeWithTestStateMachine(array $activeProcesses = [], $xmlFolder = null)
    {
        if (!$xmlFolder) {
            $xmlFolder = realpath(__DIR__ . '/../../../../_data/state-machine/');
        }

        $this->tester->setConfig(OmsConstants::PROCESS_LOCATION, $xmlFolder);
        $this->tester->setConfig(OmsConstants::ACTIVE_PROCESSES, $activeProcesses);

        return new OmsFacade();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\OmsConfig
     */
    protected function createOmsConfigMock()
    {
        return $this->getMockBuilder(OmsConfig::class)
            ->setMethods(['getProcessDefinitionLocation', 'getActiveProcesses'])
            ->getMock();
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
}
