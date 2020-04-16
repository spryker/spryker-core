<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\StateMachineItemStateTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeBridge;
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
}
