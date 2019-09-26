<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Trigger;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group TriggerTest
 * Add your own group annotations below this line
 */
class TriggerTest extends StateMachineMocks
{
    public const ITEM_IDENTIFIER = 1985;
    public const TESTING_STATE_MACHINE = 'Testing state machine';
    public const PROCESS_NAME = 'Process';
    public const INITIAL_STATE = 'new';
    public const TEST_COMMAND = 'TestCommand';

    /**
     * @return void
     */
    public function testTriggerForNewItemShouldExecutedSMAndPersistNewItem()
    {
        $stateMachinePersistenceMock = $this->createPersistenceMock();
        $stateMachinePersistenceMock->expects($this->once())
            ->method('getProcessId')
            ->willReturn(1);

        $stateMachinePersistenceMock->expects($this->once())
            ->method('getInitialStateIdByStateName')
            ->willReturn(1);

        $stateMachinePersistenceMock->expects($this->once())
            ->method('updateStateMachineItemsFromPersistence')
            ->willReturnCallback(
                function ($stateMachineItems) {
                    return $stateMachineItems;
                }
            );

        $finderMock = $this->createFinderMock();
        $finderMock->expects($this->exactly(2))
            ->method('findProcessesForItems')
            ->willReturn($this->createProcesses());

        $finderMock->expects($this->once())
            ->method('findProcessByStateMachineAndProcessName')
            ->willReturn($this->createProcesses()[static::PROCESS_NAME]);

        $finderMock->expects($this->exactly(2))
            ->method('filterItemsWithOnEnterEvent')
            ->willReturnOnConsecutiveCalls(
                $this->createStateMachineItems(),
                []
            );

        $conditionMock = $this->createTriggerConditionMock();
        $transitionLogMock = $this->createTriggerTransitionLog();

        $trigger = $this->createTrigger(
            $transitionLogMock,
            $finderMock,
            $stateMachinePersistenceMock,
            $conditionMock
        );

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_STATE_MACHINE);
        $stateMachineProcessTransfer->setProcessName(static::PROCESS_NAME);

        $affectedItems = $trigger->triggerForNewStateMachineItem($stateMachineProcessTransfer, static::ITEM_IDENTIFIER);

        $this->assertEquals(1, $affectedItems);
    }

    /**
     * @return void
     */
    public function testTriggerEventShouldTriggerSmForGiveItems()
    {
        $stateMachinePersistenceMock = $this->createTriggerPersistenceMock();
        $finderMock = $this->createTrigerFinderMock();
        $conditionMock = $this->createTriggerConditionMock();
        $transitionLogMock = $this->createTriggerTransitionLog();

        $trigger = $this->createTrigger(
            $transitionLogMock,
            $finderMock,
            $stateMachinePersistenceMock,
            $conditionMock
        );

        $stateMachineItemTransfer = $this->createTriggerStateMachineItem();
        $stateMachineItems[] = $stateMachineItemTransfer;

        $affectedItems = $trigger->triggerEvent(
            'event',
            $stateMachineItems
        );

        $this->assertEquals(1, $affectedItems);
    }

    /**
     * @return void
     */
    public function testTriggerConditionsWithoutEventShouldExecuteConditionCheckAndTriggerEvents()
    {
        $stateMachinePersistenceMock = $this->createTriggerPersistenceMock();
        $finderMock = $this->createTrigerFinderMock();
        $conditionMock = $this->createTriggerConditionMock();
        $transitionLogMock = $this->createTriggerTransitionLog();

        $conditionMock->expects($this->once())
            ->method('getOnEnterEventsForStatesWithoutTransition')
            ->willReturn($this->createStateMachineItems());

        $trigger = $this->createTrigger(
            $transitionLogMock,
            $finderMock,
            $stateMachinePersistenceMock,
            $conditionMock
        );

        $affectedItems = $trigger->triggerConditionsWithoutEvent(static::TESTING_STATE_MACHINE);

        $this->assertEquals(1, $affectedItems);
    }

    /**
     * @return void
     */
    public function testTriggerForTimeoutExpiredItemsShouldExecuteSMOnItemsWithExpiredTimeout()
    {
        $stateMachinePersistenceMock = $this->createTriggerPersistenceMock();
        $finderMock = $this->createTrigerFinderMock();
        $conditionMock = $this->createTriggerConditionMock();
        $transitionLogMock = $this->createTriggerTransitionLog();

        $stateMachinePersistenceMock->expects($this->once())
            ->method('getItemsWithExpiredTimeouts')
            ->willReturn([$this->createTriggerStateMachineItem()]);

        $trigger = $this->createTrigger(
            $transitionLogMock,
            $finderMock,
            $stateMachinePersistenceMock,
            $conditionMock
        );

        $affectedItems = $trigger->triggerForTimeoutExpiredItems(static::TESTING_STATE_MACHINE);

        $this->assertEquals(1, $affectedItems);
    }

    /**
     * @return void
     */
    public function testTriggerShouldLogTransitionsForTriggerEvent()
    {
        $stateMachinePersistenceMock = $this->createTriggerPersistenceMock();
        $finderMock = $this->createTrigerFinderMock();
        $conditionMock = $this->createTriggerConditionMock();

        $transitionLogMock = $this->createTransitionLogMock();
        $transitionLogMock->expects($this->exactly(1))->method('setEvent');

        $trigger = $this->createTrigger(
            $transitionLogMock,
            $finderMock,
            $stateMachinePersistenceMock,
            $conditionMock
        );

        $stateMachineItems[] = $this->createTriggerStateMachineItem();

        $trigger->triggerEvent(
            'event',
            $stateMachineItems
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\Process[]
     */
    protected function createProcesses()
    {
        $processes = [];
        $process = new Process();

        $event = new Event();
        $event->setName('event');
        $event->setCommand(static::TEST_COMMAND);

        $transition = new Transition();
        $state = new State();
        $state->setName('new');
        $transition->setSourceState($state);

        $event->addTransition($transition);

        $outgoingTransitions = new Transition();
        $outgoingTransitions->setEvent($event);

        $state = new State();
        $state->setName('new');
        $state->addOutgoingTransition($outgoingTransitions);

        $process->addState($state);

        $processes[static::PROCESS_NAME] = $process;

        return $processes;
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected function createStateMachineItems()
    {
        $items = [];

        $items['event'] = [];
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setProcessName(static::PROCESS_NAME);
        $stateMachineItemTransfer->setIdentifier(1);
        $stateMachineItemTransfer->setStateName('new');
        $items['event'][] = $stateMachineItemTransfer;

        return $items;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface|null $transitionLogMock
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface|null $finderMock
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface|null $persistenceMock
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface|null $conditionMock
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface|null $stateUpdaterMock
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface|null $handlerResolverMock
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\Trigger
     */
    protected function createTrigger(
        ?TransitionLogInterface $transitionLogMock = null,
        ?FinderInterface $finderMock = null,
        ?PersistenceInterface $persistenceMock = null,
        ?ConditionInterface $conditionMock = null,
        ?StateUpdaterInterface $stateUpdaterMock = null,
        ?HandlerResolverInterface $handlerResolverMock = null
    ) {
        if ($transitionLogMock === null) {
            $transitionLogMock = $this->createTransitionLogMock();
        }

        if ($handlerResolverMock === null) {
            $handlerResolverMock = $this->createHandlerResolverMock();

            $commandMock = $this->createCommandMock();

            $handlerMock = $this->createStateMachineHandlerMock();
            $handlerMock->method('getActiveProcesses')->willReturn([static::PROCESS_NAME]);
            $handlerMock->method('getInitialStateForProcess')->willReturn(static::INITIAL_STATE);
            $handlerMock->method('getCommandPlugins')->willReturn([
                static::TEST_COMMAND => $commandMock,
            ]);
            $handlerResolverMock->method('get')->willReturn($handlerMock);
        }

        if ($finderMock === null) {
            $finderMock = $this->createFinderMock();
        }

        if ($persistenceMock === null) {
            $persistenceMock = $this->createPersistenceMock();
        }

        if ($stateUpdaterMock === null) {
            $stateUpdaterMock = $this->createStateUpdaterMock();
        }

        if ($conditionMock === null) {
            $conditionMock = $this->createConditionMock();
        }

        return new Trigger(
            $transitionLogMock,
            $handlerResolverMock,
            $finderMock,
            $persistenceMock,
            $conditionMock,
            $stateUpdaterMock
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected function createTriggerPersistenceMock()
    {
        $stateMachinePersistenceMock = $this->createPersistenceMock();
        $stateMachinePersistenceMock->expects($this->once())
            ->method('updateStateMachineItemsFromPersistence')
            ->willReturnCallback(
                function ($stateMachineItems) {
                    return $stateMachineItems;
                }
            );

        return $stateMachinePersistenceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    protected function createTrigerFinderMock()
    {
        $finderMock = $this->createFinderMock();
        $finderMock->expects($this->once())
            ->method('findProcessesForItems')
            ->willReturn($this->createProcesses());

        $finderMock->expects($this->once())
            ->method('findProcessByStateMachineAndProcessName')
            ->willReturn($this->createProcesses()[static::PROCESS_NAME]);

        $finderMock->expects($this->once())
            ->method('filterItemsWithOnEnterEvent')
            ->willReturn([]);

        return $finderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface
     */
    protected function createTriggerConditionMock()
    {
        $conditionMock = $this->createConditionMock();
        $targetState = new State();
        $targetState->setName('target state');
        $conditionMock->expects($this->once())
            ->method('getTargetStatesFromTransitions')
            ->willReturn($targetState);

        return $conditionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Logger\TransitionLog
     */
    protected function createTriggerTransitionLog()
    {
        $transitionLogMock = $this->createTransitionLogMock();
        $transitionLogMock->expects($this->once())->method('init');
        $transitionLogMock->expects($this->once())->method('setEvent');
        $transitionLogMock->expects($this->exactly(2))->method('addSourceState');
        $transitionLogMock->expects($this->once())->method('addTargetState');
        $transitionLogMock->expects($this->once())->method('saveAll');

        return $transitionLogMock;
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createTriggerStateMachineItem()
    {
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier(1);
        $stateMachineItemTransfer->setStateName('new');
        $stateMachineItemTransfer->setIdItemState(1);
        $stateMachineItemTransfer->setEventName('event');
        $stateMachineItemTransfer->setProcessName(static::PROCESS_NAME);

        return $stateMachineItemTransfer;
    }
}
