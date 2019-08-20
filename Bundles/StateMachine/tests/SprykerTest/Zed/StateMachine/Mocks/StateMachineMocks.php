<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;
use Spryker\Zed\StateMachine\StateMachineConfig;

class StateMachineMocks extends Unit
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Logger\TransitionLog
     */
    protected function createTransitionLogMock()
    {
        $transitionLogMock = $this->getMockBuilder(TransitionLogInterface::class)->getMock();

        return $transitionLogMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    protected function createFinderMock()
    {
        $finderMock = $this->getMockBuilder(FinderInterface::class)->getMock();

        return $finderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected function createHandlerResolverMock()
    {
        $handlerResolverMock = $this->getMockBuilder(HandlerResolverInterface::class)->getMock();

        return $handlerResolverMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    protected function createStateMachineHandlerMock()
    {
        $stateMachineHandlerMock = $this->getMockBuilder(StateMachineHandlerInterface::class)->getMock();

        return $stateMachineHandlerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected function createPersistenceMock()
    {
        $persistenceMock = $this->getMockBuilder(PersistenceInterface::class)->getMock();

        return $persistenceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface
     */
    protected function createStateUpdaterMock()
    {
        $stateUpdaterMock = $this->getMockBuilder(StateUpdaterInterface::class)->getMock();

        return $stateUpdaterMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface
     */
    protected function createConditionPluginMock()
    {
        $conditionPluginMock = $this->getMockBuilder(ConditionPluginInterface::class)->getMock();

        return $conditionPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    public function createBuilderMock()
    {
        $builderMock = $this->getMockBuilder(BuilderInterface::class)->getMock();

        return $builderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected function createStateMachineQueryContainerMock()
    {
        $builderMock = $this->getMockBuilder(StateMachineQueryContainerInterface::class)->getMock();

        return $builderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    protected function createProcessMock()
    {
        $processMock = $this->getMockBuilder(ProcessInterface::class)->getMock();

        return $processMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    protected function createTimeoutMock()
    {
        $timeoutMock = $this->getMockBuilder(TimeoutInterface::class)->getMock();

        return $timeoutMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected function createStateMachinePersistenceMock()
    {
        $persistenceMock = $this->getMockBuilder(PersistenceInterface::class)->getMock();

        return $persistenceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function createPropelConnectionMock()
    {
        $propelConnectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        return $propelConnectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface
     */
    protected function createConditionMock()
    {
        $conditionMock = $this->getMockBuilder(ConditionInterface::class)->getMock();

        return $conditionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface
     */
    protected function createCommandMock()
    {
        $commandMock = $this->getMockBuilder(CommandPluginInterface::class)->getMock();

        return $commandMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\StateMachineConfig
     */
    protected function createStateMachineConfigMock()
    {
        $stateMachineConfigMock = $this->getMockBuilder(StateMachineConfig::class)->getMock();
        $stateMachineConfigMock->method('getStateMachineItemLockExpirationInterval')
            ->willReturn('1 minutes');

        return $stateMachineConfigMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface
     */
    protected function createTriggerMock()
    {
        $triggerLockMock = $this->getMockBuilder(TriggerInterface::class)->getMock();

        return $triggerLockMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface
     */
    protected function createItemLockMock()
    {
        $itemLockMock = $this->getMockBuilder(ItemLockInterface::class)->getMock();

        return $itemLockMock;
    }
}
