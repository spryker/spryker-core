<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business\Logger;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog;
use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;
use Spryker\Zed\StateMachine\Business\Logger\PathFinderInterface;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLog;
use Spryker\Zed\StateMachine\Business\Process\Event;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group Logger
 * @group TransitionLogTest
 * Add your own group annotations below this line
 */
class TransitionLogTest extends StateMachineMocks
{
    /**
     * @return void
     */
    public function testLoggerPersistsAllProvidedData()
    {
        $stateMachineTransitionLogEntityMock = $this->createTransitionLogEntityMock();
        $stateMachineTransitionLogEntityMock
            ->expects($this->exactly(2))
            ->method('save');

        $stateMachineItemTransfer = $this->createItemTransfer();

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

        $transitionLog->setErrorMessage('Failure');
        $transitionLog->setIsError(true);

        $event = new Event();
        $event->setName('Event');

        $transitionLog->setEvent($event);
        $transitionLog->save($stateMachineItemTransfer);
        $transitionLog->saveAll();

        $this->assertEquals(get_class($commandMock), $stateMachineTransitionLogEntityMock->getCommand());
        $this->assertEquals(get_class($conditionMock), $stateMachineTransitionLogEntityMock->getCondition());
        $this->assertEquals($sourceState, $stateMachineTransitionLogEntityMock->getSourceState());
        $this->assertEquals($targetState, $stateMachineTransitionLogEntityMock->getTargetState());
        $this->assertEquals($event->getName(), $stateMachineTransitionLogEntityMock->getEvent());
    }

    /**
     * @return void
     */
    public function testWhenNonCliRequestUsedShouldExtractOutputParamsAndPersist()
    {
        $_SERVER[TransitionLog::QUERY_STRING] = 'one=1&two=2';
        $stateMachineTransitionLogEntityMock = $this->createTransitionLogEntityMock();
        $stateMachineItemTransfer = $this->createItemTransfer();

        $transitionLog = $this->createTransitionLog($stateMachineTransitionLogEntityMock);
        $transitionLog->init([$stateMachineItemTransfer]);

        $storedParams = $stateMachineTransitionLogEntityMock->getParams();

        $this->assertEquals('one=1', $storedParams[0]);
        $this->assertEquals('two=2', $storedParams[1]);
    }

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog $stateMachineTransitionLogEntityMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Logger\TransitionLog
     */
    protected function createTransitionLog(SpyStateMachineTransitionLog $stateMachineTransitionLogEntityMock)
    {
        $partialTransitionLogMock = $this->getMockBuilder(TransitionLog::class)
            ->setMethods(['createStateMachineTransitionLogEntity'])
            ->setConstructorArgs([$this->createPathFinderMock(), $this->createUtilNetworkServiceMock()])
            ->getMock();

        $partialTransitionLogMock->method('createStateMachineTransitionLogEntity')
            ->willReturn($stateMachineTransitionLogEntityMock);

        return $partialTransitionLogMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Logger\PathFinderInterface
     */
    protected function createPathFinderMock()
    {
        return $this->getMockBuilder(PathFinderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog
     */
    protected function createTransitionLogEntityMock()
    {
        return $this->getMockBuilder(SpyStateMachineTransitionLog::class)->setMethods(['save'])->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected function createUtilNetworkServiceMock()
    {
        $utilNetworkServiceMock = $this->getMockBuilder(UtilNetworkServiceInterface::class)->setMethods(['getHostName', 'getRequestId'])->getMock();
        $utilNetworkServiceMock->method('getHostName')->willReturn('hostname-mock');

        return $utilNetworkServiceMock;
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createItemTransfer()
    {
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier(1);
        $stateMachineItemTransfer->setEventName('event');
        $stateMachineItemTransfer->setIdItemState(1);
        $stateMachineItemTransfer->setStateName('state');
        $stateMachineItemTransfer->setProcessName('process');
        $stateMachineItemTransfer->setIdStateMachineProcess(1);

        return $stateMachineItemTransfer;
    }
}
