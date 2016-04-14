<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Propel\Runtime\Propel;

class StateUpdater implements StateUpdaterInterface
{
    /**
     * @var TimeoutInterface
     */
    protected $timeout;

    /**
     * @var HandlerResolverInterface
     */
    protected $stateMachineHandlerResolver;

    /**
     * @var PersistenceInterface
     */
    protected $stateMachinePersitence;

    /**
     * @param TimeoutInterface $timeout
     * @param HandlerResolverInterface $stateMachineHandlerResolver
     * @param PersistenceInterface $stateMachinePersitence
     */
    public function __construct(
        TimeoutInterface $timeout,
        HandlerResolverInterface $stateMachineHandlerResolver,
        PersistenceInterface $stateMachinePersitence
    ) {
        $this->timeout = $timeout;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->stateMachinePersitence = $stateMachinePersitence;
    }

    /**
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    public function updateStateMachineItemState(
        $stateMachineName,
        array $stateMachineItems,
        array $processes,
        array $sourceStateBuffer
    ) {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $currentDate = new \DateTime('now');
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $process = $processes[$stateMachineItemTransfer->getProcessName()];

            $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
            $targetState = $stateMachineItemTransfer->getStateName();

            if ($sourceState !== $targetState) {
                $this->timeout->dropOldTimeout($process, $sourceState, $stateMachineItemTransfer);
                $this->timeout->setNewTimeout($process, $stateMachineItemTransfer, $currentDate);

                $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);
                $stateMachineHandler->itemStateUpdated($stateMachineItemTransfer);

                $this->stateMachinePersitence->saveItemStateHistory($stateMachineItemTransfer);

            }
        }

        $connection->commit();
    }
}
