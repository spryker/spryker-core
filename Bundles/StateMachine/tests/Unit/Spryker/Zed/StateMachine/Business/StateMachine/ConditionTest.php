<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business;

use Generated\Shared\Transfer\StateMachineItemTransfer;

use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\Condition;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

class ConditionTest extends StateMachineMocks
{

    /**
     * @return void
     */
    public function testCheckConditionForTransitionShouldReturnTargetStateOfGivenTransition()
    {
        $stateMachineHandlerResolverMock = $this->createHandlerResolverMock();

        $stateMachineHandler = $this->createStateMachineHandlerMock();

        $conditionPluginMock = $this->createConditionPluginMock();
        $conditionPluginMock->expects($this->once())
            ->method('check')
            ->willReturn(true);

        $stateMachineHandler->expects($this->exactly(2))
            ->method('getConditionPlugins')
            ->willReturn([
               'condition' => $conditionPluginMock
            ]
        );

        $stateMachineHandlerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($stateMachineHandler);

        $condition = new Condition(
            $this->createTransitionLogMock(),
            $stateMachineHandlerResolverMock,
            $this->createFinderMock(),
            $this->createPersistenceMock(),
            $this->createStateUpdaterMock()
        );


        $transitions = [];
        $sourceState = new State();
        $sourceState->setName('source state');

        $targetState = new State();
        $targetState->setName('target state');

        $transition = new Transition();
        $transition->setCondition('condition');
        $transition->setSource($sourceState);
        $transition->setTarget($targetState);
        $transitions[] = $transition;

        $stateMachineItemTransfer = new StateMachineItemTransfer();

        $sourcesState = new State();

        $targetState = $condition->checkConditionForTransitions(
            'Test',
            $transitions,
            $stateMachineItemTransfer,
            $sourcesState,
            $this->createTransitionLogMock()
        );

        $this->assertEquals($targetState->getName(), $targetState->getName());
    }




}
