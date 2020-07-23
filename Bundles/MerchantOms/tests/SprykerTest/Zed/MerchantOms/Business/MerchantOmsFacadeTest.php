<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StateMachineItemStateTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\MerchantOms\Business\Exception\MerchantNotFoundException;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeBridge;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeBridge;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOms
 * @group Business
 * @group Facade
 * @group MerchantOmsFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantOmsFacadeTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';
    protected const TEST_STATE_MACHINE_EVENT = 'test';
    protected const TEST_PROCESS_NAME = 'processName';
    protected const TEST_STATE_NAMES = ['new', 'canceled'];

    /**
     * @var \SprykerTest\Zed\MerchantOms\MerchantOmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $stateMachineFacadeMock = $this->createMock(StateMachineFacade::class);
        $stateMachineFacadeMock->method('triggerEventForItems')->willReturn(1);

        $this->tester->setDependency(
            MerchantOmsDependencyProvider::FACADE_STATE_MACHINE,
            new MerchantOmsToStateMachineFacadeBridge($stateMachineFacadeMock)
        );
    }

    /**
     * @return void
     */
    public function testGetStateMachineItemsByStateIdsReturnsCorrectData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $processEntity = $this->tester->haveStateMachineProcess();

        $stateEntity = $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $processEntity->getIdStateMachineProcess(),
        ]);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateEntity->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);

        // Act
        $stateMachineItemTransfers = $this->tester->getFacade()->getStateMachineItemsByStateIds([$stateEntity->getIdStateMachineItemState()]);
        $stateMachineItemTransfer = $stateMachineItemTransfers[0] ?? null;

        // Assert
        $this->assertCount(1, $stateMachineItemTransfers);
        $this->assertInstanceOf(StateMachineItemTransfer::class, $stateMachineItemTransfer);
        $this->assertSame((int)$stateMachineItemTransfer->getIdItemState(), (int)$stateEntity->getIdStateMachineItemState());
        $this->assertSame((int)$stateMachineItemTransfer->getIdentifier(), (int)$merchantOrderItemTransfer->getIdMerchantOrderItem());
    }

    /**
     * @return void
     */
    public function testExpandMerchantOrderWithMerchantOmsDataReturnsCorrectData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $expectedMerchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $stateMachineProcessEntity = $this->tester->haveStateMachineProcess();

        $stateMachineItemStateTransfer = $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $stateMachineProcessEntity->getIdStateMachineProcess(),
        ]);

        $expectedMerchantOrderTransfer->setItemStates([$stateMachineItemStateTransfer->getName()]);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateMachineItemStateTransfer->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $expectedMerchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);
        $expectedMerchantOrderTransfer->addMerchantOrderItem($merchantOrderItemTransfer);

        // Act
        $merchantOrderTransfer = $this->tester->getFacade()->expandMerchantOrderWithMerchantOmsData($expectedMerchantOrderTransfer);

        // Assert
        $this->assertSame($expectedMerchantOrderTransfer->getItemStates(), $merchantOrderTransfer->getItemStates());
    }

    /**
     * @return void
     */
    public function testTriggerEventForMerchantOrderItemReturnsSuccess(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $processEntity = $this->tester->haveStateMachineProcess();

        $stateEntity = $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $processEntity->getIdStateMachineProcess(),
        ]);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateEntity->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);

        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOrderItemReference($merchantOrderItemTransfer->getMerchantOrderItemReference())
            ->setMerchantOmsEventName(static::TEST_STATE_MACHINE_EVENT);

        // Act
        $merchantOmsTriggerResponseTransfer = $this->tester->getFacade()->triggerEventForMerchantOrderItem($merchantOmsTriggerRequestTransfer);

        // Assert
        $this->assertTrue($merchantOmsTriggerResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testTriggerEventForMerchantOrderItemReturnsFalseWithInvalidItemReference(): void
    {
        // Arrange
        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOrderItemReference('invalid reference')
            ->setMerchantOmsEventName(static::TEST_STATE_MACHINE_EVENT);

        // Act
        $merchantOmsTriggerResponseTransfer = $this->tester->getFacade()->triggerEventForMerchantOrderItem($merchantOmsTriggerRequestTransfer);

        // Assert
        $this->assertFalse($merchantOmsTriggerResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetMerchantOmsProcessByMerchantThrowsExceptionIfMerchantNotFound(): void
    {
        // Arrange
        $this->setMerchantFacadeMockDependency(null);

        // Assert
        $this->expectException(MerchantNotFoundException::class);

        // Act
        $this->tester->getFacade()->getMerchantOmsProcessByMerchant(new MerchantCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testGetMerchantOmsProcessByMerchantReturnsStateMachineProcessWithStateNames(): void
    {
        // Arrange
        $stateMachineProcessTransfer = (new StateMachineProcessTransfer())
            ->setProcessName(static::TEST_PROCESS_NAME)
            ->setStateMachineName(static::TEST_STATE_MACHINE);
        $this->setMerchantFacadeMockDependency(new MerchantTransfer());
        $this->setStateMachineFacadeMockDependency(static::TEST_STATE_NAMES, $stateMachineProcessTransfer);

        // Act
        $stateMachineProcessTransfer = $this->tester->getFacade()->getMerchantOmsProcessByMerchant(new MerchantCriteriaTransfer());

        // Assert
        $this->assertEquals(static::TEST_STATE_NAMES, $stateMachineProcessTransfer->getStateNames());
        $this->assertEquals(static::TEST_PROCESS_NAME, $stateMachineProcessTransfer->getProcessName());
        $this->assertEquals(static::TEST_STATE_MACHINE, $stateMachineProcessTransfer->getStateMachineName());
    }

    /**
     * @return void
     */
    public function testGetMerchantOmsProcessByMerchantReturnsDefaultStateMachineProcessWithStateNames(): void
    {
        // Arrange
        /** @var \Spryker\Zed\MerchantOms\MerchantOmsConfig $merchantOmsConfig */
        $merchantOmsConfig = $this->tester->getModuleConfig();
        $this->setMerchantFacadeMockDependency(new MerchantTransfer());
        $this->setStateMachineFacadeMockDependency(static::TEST_STATE_NAMES);

        // Act
        $stateMachineProcessTransfer = $this->tester->getFacade()->getMerchantOmsProcessByMerchant(new MerchantCriteriaTransfer());

        // Assert
        $this->assertEquals(static::TEST_STATE_NAMES, $stateMachineProcessTransfer->getStateNames());
        $this->assertEquals($merchantOmsConfig->getMerchantOmsDefaultProcessName(), $stateMachineProcessTransfer->getProcessName());
        $this->assertEquals($merchantOmsConfig::MERCHANT_OMS_STATE_MACHINE_NAME, $stateMachineProcessTransfer->getStateMachineName());
    }

    /**
     * @param string[] $stateNames
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer|null $stateMachineProcessTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected function setStateMachineFacadeMockDependency(
        array $stateNames,
        ?StateMachineProcessTransfer $stateMachineProcessTransfer = null
    ): MerchantOmsToStateMachineFacadeInterface {
        $stateMachineFacadeMock = $this->getMockBuilder(MerchantOmsToStateMachineFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $stateMachineFacadeMock->method('findStateMachineProcess')->willReturn($stateMachineProcessTransfer);
        $stateMachineFacadeMock->method('getProcessStateNames')->willReturn($stateNames);

        $this->tester->setDependency(MerchantOmsDependencyProvider::FACADE_STATE_MACHINE, $stateMachineFacadeMock);

        return $stateMachineFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $merchantTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface
     */
    protected function setMerchantFacadeMockDependency(?MerchantTransfer $merchantTransfer): MerchantOmsToMerchantFacadeInterface
    {
        $merchantFacadeMock = $this->getMockBuilder(MerchantOmsToMerchantFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $merchantFacadeMock->method('findOne')->willReturn($merchantTransfer);

        $this->tester->setDependency(MerchantOmsDependencyProvider::FACADE_MERCHANT, $merchantFacadeMock);

        return $merchantFacadeMock;
    }

    /**
     * @return void
     */
    public function testFindCurrentStateByIdSalesOrderItemReturnsStateForExistingOrderItem(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $processEntity = $this->tester->haveStateMachineProcess();

        $stateEntity = $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $processEntity->getIdStateMachineProcess(),
        ]);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateEntity->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);

        // Act
        $stateMachineItemTransfer = $this->tester->getFacade()->findCurrentStateByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        // Assert
        $this->assertInstanceOf(StateMachineItemTransfer::class, $stateMachineItemTransfer);
        $this->assertSame($stateEntity->getName(), $stateMachineItemTransfer->getStateName());
    }

    /**
     * @return void
     */
    public function testFindCurrentStateByIdSalesOrderItemReturnsNullForNotExistingOrderItem(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $processEntity = $this->tester->haveStateMachineProcess();

        $stateEntity = $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $processEntity->getIdStateMachineProcess(),
        ]);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateEntity->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);

        // Act
        $stateMachineItemTransfer = $this->tester->getFacade()->findCurrentStateByIdSalesOrderItem(999);

        // Assert
        $this->assertNull($stateMachineItemTransfer);
    }
}
