<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Finder;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

class FinderTest extends StateMachineMocks
{
    /**
     * @return void
     */
    public function testGetActiveProcessShouldReturnProcessesRegisteredByHandler()
    {
        $statemachineHandlerMock = $this->createStateMachineHandlerMock();
        $statemachineHandlerMock->expects($this->once())
            ->method('getActiveProcesses')
            ->willReturn(['Process1', 'Process2']);

        $handlerResolverMock = $this->createHandlerResolverMock();
        $handlerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($statemachineHandlerMock);

        $finder = $this->createFinder($handlerResolverMock);

        $subProcesses = $finder->getProcesses('TestStateMachine');

        $this->assertCount(2, $subProcesses);

        /* @var $subProcess StateMachineProcessTransfer  */
        $subProcess = array_pop($subProcesses);
        $this->assertInstanceOf(StateMachineProcessTransfer::class, $subProcess);
        $this->assertEquals('TestStateMachine', $subProcess->getStateMachineName());
        $this->assertEquals('Process2', $subProcess->getProcessName());

    }

    /**
     * @param BuilderInterface $builderMock
     * @param HandlerResolverInterface $handlerResolverMock
     * @param StateMachineHandlerInterface $stateMachineQueryContainerMock
     *
     * @return Finder
     */
    protected function createFinder(
        HandlerResolverInterface $handlerResolverMock = null,
        BuilderInterface $builderMock = null,
        StateMachineHandlerInterface $stateMachineQueryContainerMock = null
    )
    {
        if ($builderMock === null) {
            $builderMock = $this->createBuilderMock();
        }

        if ($handlerResolverMock === null) {
            $handlerResolverMock = $this->createHandlerResolverMock();
        }

        if ($stateMachineQueryContainerMock === null) {
            $stateMachineQueryContainerMock = $this->createStateMachineQueryContainerMock();
        }

        return new Finder(
            $builderMock,
            $handlerResolverMock,
            $stateMachineQueryContainerMock
        );
    }
}
