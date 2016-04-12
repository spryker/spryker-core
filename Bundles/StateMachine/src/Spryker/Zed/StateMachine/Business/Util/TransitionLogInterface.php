<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Util;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface;

interface TransitionLogInterface
{

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event);

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return void
     */
    public function init(array $stateMachineItems);

    /**
     * @param StateMachineItemTransfer $stateMachineItem
     * @param CommandPluginInterface $command
     *
     * @return void
     */
    public function addCommand(StateMachineItemTransfer $stateMachineItem, CommandPluginInterface $command);

    /**
     * @param StateMachineItemTransfer $stateMachineItem
     * @param ConditionPluginInterface $condition
     *
     */
    public function addCondition(StateMachineItemTransfer $stateMachineItem, ConditionPluginInterface $condition);

    /**
     * @param StateMachineItemTransfer $stateMachineItem
     * @param string $stateName
     *
     * @return void
     */
    public function addSourceState(StateMachineItemTransfer $stateMachineItem, $stateName);

    /**
     * @param StateMachineItemTransfer $stateMachineItem
     * @param string $stateName
     *
     * @return void
     */
    public function addTargetState(StateMachineItemTransfer $stateMachineItem, $stateName);

    /**
     * @param bool $error
     *
     * @return void
     */
    public function setIsError($error);

    /**
     * @param StateMachineItemTransfer $stateMachineItem
     *
     * @return void
     */
    public function save(StateMachineItemTransfer $stateMachineItem);

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage($errorMessage);

    /**
     * @return void
     */
    public function saveAll();

}
