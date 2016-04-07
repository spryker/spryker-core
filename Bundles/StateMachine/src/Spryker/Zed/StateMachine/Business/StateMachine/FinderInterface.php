<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface FinderInterface
{
    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getProcesses();

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems);

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return StateMachineItemTransfer[]
     */
    public function getStateMachineItemsFromPersistence(array $stateMachineItems);

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return StateMachineItemTransfer
     */
    public function getStateMachineItemFromPersistence(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param int $identifier
     *
     * @return StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($identifier);

}
