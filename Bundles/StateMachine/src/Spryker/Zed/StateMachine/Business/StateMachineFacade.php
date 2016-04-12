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
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return bool
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        return $this->getFactory()
            ->createStateMachine(
                $stateMachineProcessTransfer->getStateMachineName()
            )
            ->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
    }

    /**
     * @api
     *
     * @param string $eventName
     * @param string $stateMachineName
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function triggerEvent($eventName, $stateMachineName, StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createStateMachine($stateMachineName)
            ->triggerEvent($eventName, [$stateMachineItemTransfer]);
    }

    /**
     * @api
     *
     * @param string $eventName
     * @param string $stateMachineName
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return bool
     */
    public function triggerEventForItems($eventName, $stateMachineName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachine($stateMachineName)
            ->triggerEvent($eventName, $stateMachineItems);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @return \Spryker\Zed\Oms\Business\Process\Process[]
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
            ->createStateMachine($stateMachineName)
            ->checkConditions();
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
        $orderStateMachine = $this->getFactory()
            ->createStateMachine($stateMachineName);

        return $this->getFactory()
            ->createStateMachineTimeout($stateMachineName)
            ->checkTimeouts($orderStateMachine);
    }

    /**
     * @api
     *
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
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
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getStateMachineProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return $this->getFactory()
            ->createStateMachinePersistenceManager()
            ->getProcessId($stateMachineProcessTransfer);
    }

    /**
     * @api
     * @todo use transfer object for event.
     *
     * @param string $stateMachineName
     * @param StateMachineItemTransfer $stateMachineItemTransfer
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
     * @todo use transfer object for event.
     *
     * @param string $stateMachineName
     * @param StateMachineItemTransfer[] $stateMachineItems
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
            ->createStateMachineFinder($stateMachineName)
            ->getProcessedStateMachineItemTransfer($idState, $idStateMachineProcess, $identifier);
    }

    /**
     * @api
     *
     * @param string  $stateMachineName
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems($stateMachineName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineName)
            ->getProcessedStateMachineItems($stateMachineItems);
    }

    /**
     * @param string $stateMachineName
     * @param int $idStateMachineProcess
     * @param int $identifier
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($stateMachineName, $idStateMachineProcess, $identifier)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineName)
            ->getStateHistoryByStateItemIdentifier($identifier, $idStateMachineProcess);
    }

    /**
     * @api
     *
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return StateMachineItemTransfer[]
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
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return StateMachineItemTransfer[]
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName)
    {
        return $this->getFactory()
            ->createStateMachineFinder($stateMachineProcessTransfer->getStateMachineName())
            ->getItemsWithoutFlag($stateMachineProcessTransfer, $flagName);
    }
}
