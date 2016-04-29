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
class StateMachineFacade extends AbstractFacade implements StateMachineFacadeInterface
{

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        return $this->getFactory()
            ->createLockedStateMachineTrigger()
            ->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function triggerEvent($eventName, StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createLockedStateMachineTrigger()
            ->triggerEvent($eventName, [$stateMachineItemTransfer]);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function triggerEventForItems($eventName, array $stateMachineItems)
    {
        return $this->getFactory()
            ->createLockedStateMachineTrigger()
            ->triggerEvent($eventName, $stateMachineItems);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getProcesses($stateMachineName)
    {
        return $this->getFactory()
            ->createStateMachineFinder()
            ->getProcesses($stateMachineName);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function checkConditions($stateMachineName)
    {
        return $this->getFactory()
            ->createLockedStateMachineTrigger()
            ->triggerConditionsWithoutEvent($stateMachineName);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function checkTimeouts($stateMachineName)
    {
        return $this->getFactory()
            ->createLockedStateMachineTrigger()
            ->triggerForTimeoutExpiredItems($stateMachineName);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function drawProcess(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $highlightState = null,
        $format = null,
        $fontSize = null
    ) {
        $process = $this->getFactory()
            ->createStateMachineBuilder()
            ->createProcess($stateMachineProcessTransfer);

        return $this->getFactory()
            ->createGraphDrawer(
                $stateMachineProcessTransfer->getStateMachineName()
            )->draw($process, $highlightState, $format, $fontSize);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getStateMachineProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return $this->getFactory()
            ->createStateMachinePersistence()
            ->getProcessId($stateMachineProcessTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createStateMachineFinder()
            ->getManualEventsForStateMachineItem($stateMachineItemTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachineFinder()
            ->getManualEventsForStateMachineItems($stateMachineItems);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getProcessedStateMachineItemTransfer(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createStateMachinePersistence()
            ->getProcessedStateMachineItemTransfer($stateMachineItemTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getProcessedStateMachineItems(array $stateMachineItems)
    {
        return $this->getFactory()
            ->createStateMachinePersistence()
            ->getProcessedStateMachineItems($stateMachineItems);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getStateHistoryByStateItemIdentifier($idStateMachineProcess, $identifier)
    {
        return $this->getFactory()
            ->createStateMachinePersistence()
            ->getStateHistoryByStateItemIdentifier($identifier, $idStateMachineProcess);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName)
    {
        return $this->getFactory()
            ->createStateMachineFinder()
            ->getItemsWithFlag($stateMachineProcessTransfer, $flagName);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName)
    {
        return $this->getFactory()
            ->createStateMachineFinder()
            ->getItemsWithoutFlag($stateMachineProcessTransfer, $flagName);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function clearLocks()
    {
        $this->getFactory()->createItemLock()->clearLocks();
    }

}
