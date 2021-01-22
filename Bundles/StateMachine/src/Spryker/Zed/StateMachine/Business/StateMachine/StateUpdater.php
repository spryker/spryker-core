<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class StateUpdater implements StateUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    protected $timeout;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected $stateMachineHandlerResolver;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected $stateMachinePersistence;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $stateMachineQueryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface $timeout
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface $stateMachineHandlerResolver
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $stateMachinePersistence
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $stateMachineQueryContainer
     */
    public function __construct(
        TimeoutInterface $timeout,
        HandlerResolverInterface $stateMachineHandlerResolver,
        PersistenceInterface $stateMachinePersistence,
        StateMachineQueryContainerInterface $stateMachineQueryContainer
    ) {
        $this->timeout = $timeout;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->stateMachinePersistence = $stateMachinePersistence;
        $this->stateMachineQueryContainer = $stateMachineQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param string[] $sourceStates
     *
     * @return void
     */
    public function updateStateMachineItemState(
        array $stateMachineItems,
        array $processes,
        array $sourceStates
    ) {
        if (count($stateMachineItems) === 0) {
            return;
        }

        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $this->handleDatabaseTransaction(function () use ($processes, $sourceStates, $stateMachineItemTransfer) {
                $this->executeUpdateItemStateTransaction($processes, $sourceStates, $stateMachineItemTransfer);
            });
        }
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param string[] $sourceStates
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function executeUpdateItemStateTransaction(
        array $processes,
        array $sourceStates,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        $this->assertStateMachineItemHaveRequiredData($stateMachineItemTransfer);

        $process = $processes[$stateMachineItemTransfer->getProcessName()];

        $this->assertSourceStateExists($sourceStates, $stateMachineItemTransfer);

        $sourceState = $sourceStates[$stateMachineItemTransfer->getIdentifier()];
        $targetState = $stateMachineItemTransfer->getStateName();

        $this->transitionState($sourceState, $targetState, $stateMachineItemTransfer, $process);
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection()
    {
        return $this->stateMachineQueryContainer->getConnection();
    }

    /**
     * @param array $sourceStateBuffer
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return void
     */
    protected function assertSourceStateExists(
        array $sourceStateBuffer,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        if (!isset($sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()])) {
            throw new StateMachineException(
                sprintf('Could not update state, source state not found.')
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function assertStateMachineItemHaveRequiredData(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireProcessName()
            ->requireStateMachineName()
            ->requireIdentifier()
            ->requireStateName();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function notifyHandlerStateChanged(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineItemTransfer->getStateMachineName());

        $stateMachineHandler->itemStateUpdated($stateMachineItemTransfer);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param string $sourceState
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function updateTimeouts(
        ProcessInterface $process,
        $sourceState,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        $this->timeout->dropOldTimeout($process, $sourceState, $stateMachineItemTransfer);
        $this->timeout->setNewTimeout($process, $stateMachineItemTransfer);
    }

    /**
     * @param string $sourceState
     * @param string $targetState
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    protected function transitionState(
        $sourceState,
        $targetState,
        StateMachineItemTransfer $stateMachineItemTransfer,
        ProcessInterface $process
    ) {
        if ($sourceState === $targetState) {
            return;
        }
        $this->updateTimeouts($process, $sourceState, $stateMachineItemTransfer);
        $this->notifyHandlerStateChanged($stateMachineItemTransfer);
        $this->stateMachinePersistence->saveItemStateHistory($stateMachineItemTransfer);
    }
}
