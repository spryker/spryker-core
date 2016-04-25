<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\Logger;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLog;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

class TransitionLogTest extends StateMachineMocks
{

    /**
     * @return void
     */
    public function testLoggerPersistsAllProvidedData()
    {
        $stateMachineTransitionLogEntityMock = $this->createTransitionLogEntityMock();
        $stateMachineTransitionLogEntityMock->expects($this->once())->method('save');

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier(1);
        $stateMachineItemTransfer->setEventName('event');
        $stateMachineItemTransfer->setIdItemState(1);
        $stateMachineItemTransfer->setStateName('state');
        $stateMachineItemTransfer->setProcessName('process');
        $stateMachineItemTransfer->setIdStateMachineProcess(1);

        $transitionLog = $this->createTransitionLog($stateMachineTransitionLogEntityMock);
        $transitionLog->init([$stateMachineItemTransfer]);

        $commandMock = $this->createCommandMock();
        $transitionLog->addCommand($stateMachineItemTransfer, $commandMock);

        $conditionMock = $this->createConditionPluginMock();
        $transitionLog->addCondition($stateMachineItemTransfer, $conditionMock);

        $sourceState = 'source state';
        $transitionLog->addSourceState($stateMachineItemTransfer, $sourceState);

        $targetState = 'target state';
        $transitionLog->addTargetState($stateMachineItemTransfer, $targetState);

        $event = new Event();
        $event->setName('Event');

        $transitionLog->setEvent($event);
        $transitionLog->saveAll();

        $this->assertEquals(get_class($commandMock), $stateMachineTransitionLogEntityMock->getCommand());
        $this->assertEquals(get_class($conditionMock), $stateMachineTransitionLogEntityMock->getCondition());
        $this->assertEquals($sourceState, $stateMachineTransitionLogEntityMock->getSourceState());
        $this->assertEquals($targetState, $stateMachineTransitionLogEntityMock->getTargetState());
        $this->assertEquals($event->getName(), $stateMachineTransitionLogEntityMock->getEvent());
    }


    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog $stateMachineTransitionLogEntityMock
     * @return \Spryker\Zed\StateMachine\Business\Logger\TransitionLog
     *
     */
    protected function createTransitionLog(SpyStateMachineTransitionLog $stateMachineTransitionLogEntityMock)
    {
        $partialTransitionLogMock = $this->getMock(TransitionLog::class, ['createStateMachineTransitionLogEntity']);

        $partialTransitionLogMock->method('createStateMachineTransitionLogEntity')
            ->willReturn($stateMachineTransitionLogEntityMock);

        return $partialTransitionLogMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog
     */
    protected function createTransitionLogEntityMock()
    {
        return $this->getMock(SpyStateMachineTransitionLog::class, ['save']);
    }

}
