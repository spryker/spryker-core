<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class StateUpdater implements StateUpdaterInterface
{

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
     * @param string[] $sourceStateBuffer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function updateStateMachineItemState(
        array $stateMachineItems,
        array $processes,
        array $sourceStateBuffer
    ) {

        if (count($stateMachineItems) === 0) {
            return;
        }

        $this->getConnection()->beginTransaction();

        try {
            foreach ($stateMachineItems as $stateMachineItemTransfer) {
                $stateMachineItemTransfer->requireProcessName()
                    ->requireStateMachineName()
                    ->requireIdentifier()
                    ->requireStateName();

                $process = $processes[$stateMachineItemTransfer->getProcessName()];

                if (!isset($sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()])) {
                    throw new StateMachineException(
                        sprintf('Could not update state, source state not found.')
                    );
                }

                $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
                $targetState = $stateMachineItemTransfer->getStateName();

                if ($sourceState !== $targetState) {

                    $this->assertTransitionAlreadyProcessed($stateMachineItemTransfer, $sourceState, $targetState);

                    $this->timeout->dropOldTimeout($process, $sourceState, $stateMachineItemTransfer);
                    $this->timeout->setNewTimeout($process, $stateMachineItemTransfer);

                    $stateMachineHandler = $this->stateMachineHandlerResolver
                        ->get($stateMachineItemTransfer->getStateMachineName());

                    $stateMachineHandler->itemStateUpdated($stateMachineItemTransfer);

                    $this->stateMachinePersistence->saveItemStateHistory($stateMachineItemTransfer);

                }
            }
        } catch (\Exception $e) {
            $this->getConnection()->rollBack();
            throw $e;
        }

        $this->getConnection()->commit();
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection()
    {
        return $this->stateMachineQueryContainer->getConnection();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $sourceState
     * @param string $targetState
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     * @return void
     */
    protected function assertTransitionAlreadyProcessed(
        StateMachineItemTransfer $stateMachineItemTransfer,
        $sourceState,
        $targetState
    ) {
        $alreadyTransitioned = $this->stateMachineQueryContainer->queryLastHistoryItem(
            $stateMachineItemTransfer,
            $stateMachineItemTransfer->getIdItemState()
        )->count();

        if ($alreadyTransitioned > 0) {
            throw new StateMachineException(
                sprintf(
                    'Transition between "%s" -> "%s" already processed.',
                    $sourceState,
                    $targetState
                )
            );
        }
    }

}
