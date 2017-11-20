<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Logger;

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
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return void
     */
    public function init(array $stateMachineItems);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItem
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface $command
     *
     * @return void
     */
    public function addCommand(StateMachineItemTransfer $stateMachineItem, CommandPluginInterface $command);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItem
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface $condition
     *
     * @return void
     */
    public function addCondition(StateMachineItemTransfer $stateMachineItem, ConditionPluginInterface $condition);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItem
     * @param string $stateName
     *
     * @return void
     */
    public function addSourceState(StateMachineItemTransfer $stateMachineItem, $stateName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItem
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
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItem
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
