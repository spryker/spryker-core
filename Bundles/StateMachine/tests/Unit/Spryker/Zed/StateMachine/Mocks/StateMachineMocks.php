<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Mocks;

use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLog;

class StateMachineMocks extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|TransitionLog
     */
    public function createTransitionLogMock()
    {
        $transitionLogMock = $this->getMock(TransitionLogInterface::class);

        return $transitionLogMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FinderInterface
     */
    public function createFinderMock()
    {
        $finderMock = $this->getMock(FinderInterface::class);

        return $finderMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|HandlerResolverInterface
     */
    public function createHandlerResolverMock()
    {
        $handlerResolverMock = $this->getMock(HandlerResolverInterface::class);

        return $handlerResolverMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StateMachineHandlerInterface
     */
    public function createStateMachineHandlerMock()
    {
        $stateMachineHandlerMock = $this->getMock(StateMachineHandlerInterface::class);

        return $stateMachineHandlerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PersistenceInterface
     */
    public function createPersistenceMock()
    {
        $persistenceMock = $this->getMock(PersistenceInterface::class);

        return $persistenceMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StateUpdaterInterface
     */
    public function createStateUpdaterMock()
    {
        $stateUpdaterMock = $this->getMock(StateUpdaterInterface::class);

        return $stateUpdaterMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StateMachineHandlerInterface
     */
    public function createStateMachineMock()
    {
        $stateMachineMock = $this->getMock(StateMachineHandlerInterface::class);

        return $stateMachineMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ConditionPluginInterface
     */
    public function createConditionPluginMock()
    {
        $conditionPluginMock = $this->getMock(ConditionPluginInterface::class);

        return $conditionPluginMock;
    }
}
