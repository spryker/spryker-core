<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Spryker\Zed\StateMachine\Business;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory getFactory()
 */
class StateMachineFacade extends AbstractFacade
{

    /**
     * Specification:
     *  - Returns boolean true if trigger was successful
     *  - Creates new process item in persistence if does not exist
     *  - Creates new state item in persistence if does not exist
     *  - Triggers event provided by StateMachineHandlerInterface::getInitialStateForProcess
     *  - Triggers StateMachineHandlerInterface::itemStateUpdated when state changed happens
     *  - Persist state history
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier - this is id of foreign entity you want to track in state machine, it's stored in history table.
     *
     * @return bool
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        return $this->getFactory()
            ->createStateMachineTrigger(
                $stateMachineProcessTransfer->getStateMachineName()
            )
            ->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
    }

    /**
     * @api
     *
     * @param string $eventName
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function triggerEvent($eventName, $stateMachineName, StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createStateMachineTrigger($stateMachineName)
            ->triggerEvent($eventName, [$stateMachineItemTransfer]);
    }

    /**
     * @api
     *
     * @param string $eventName
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return bool
     */
    public function triggerEventForItems($eventName, $stateMachineName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachineTrigger($stateMachineName)
            ->triggerEvent($eventName, $stateMachineItems);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\Process\Process[]
     */
    public function getProcesses($stateMachineName)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineName)
            ->getProcesses();
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @return bool
     */
    public function checkConditions($stateMachineName)
    {
        return $this->getFactory()
            ->createStateMachineTrigger($stateMachineName)
            ->triggerConditionsWithoutEvent();
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     *
     * @return int
     */
    public function checkTimeouts($stateMachineName)
    {
        $stateMachineTrigger = $this->getFactory()
            ->createStateMachineTrigger($stateMachineName);

        return $this->getFactory()
            ->createStateMachineTimeout($stateMachineName)
            ->checkTimeouts($stateMachineTrigger);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $highlightState
     * @param string $format
     * @param int $fontSize
     *
     * @return bool
     */
    public function drawProcess(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $highlightState = null,
        $format = null,
        $fontSize = null
    ) {
        $process = $this->getFactory()
            ->createStateMachineBuilder(
                $stateMachineProcessTransfer->getStateMachineName()
            )
            ->createProcess($stateMachineProcessTransfer);

        return $process->draw($highlightState, $format, $fontSize);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getStateMachineProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return $this->getFactory()
            ->createStateMachinePersistence($stateMachineProcessTransfer->getStateMachineName())
            ->getProcessId($stateMachineProcessTransfer);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItem(
        $stateMachineName,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineName)
            ->getManualEventsForStateMachineItem($stateMachineItemTransfer);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItems($stateMachineName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineName)
            ->getManualEventsForStateMachineItems($stateMachineItems);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param int $idState
     * @param int $idStateMachineProcess
     * @param int $identifier
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getProcessedStateMachineItemTransfer(
        $stateMachineName,
        $idState,
        $idStateMachineProcess,
        $identifier
    ) {
        return $this->getFactory()
            ->createStateMachinePersistence($stateMachineName)
            ->getProcessedStateMachineItemTransfer($idState, $idStateMachineProcess, $identifier);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems($stateMachineName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachinePersistence($stateMachineName)
            ->getProcessedStateMachineItems($stateMachineItems);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param int $idStateMachineProcess
     * @param int $identifier
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($stateMachineName, $idStateMachineProcess, $identifier)
    {
        return $this->getFactory()
            ->createStateMachinePersistence($stateMachineName)
            ->getStateHistoryByStateItemIdentifier($identifier, $idStateMachineProcess);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineProcessTransfer->getStateMachineName())
            ->getItemsWithFlag($stateMachineProcessTransfer, $flagName);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineProcessTransfer->getStateMachineName())
            ->getItemsWithoutFlag($stateMachineProcessTransfer, $flagName);
    }

}
