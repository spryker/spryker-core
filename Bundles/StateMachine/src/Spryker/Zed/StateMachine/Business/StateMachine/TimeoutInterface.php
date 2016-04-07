<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;

interface TimeoutInterface
{

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\StateMachineInterface $stateMachine
     *
     * @return int
     */
    public function checkTimeouts(StateMachineInterface $stateMachine);

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     * @param \DateTime $currentTime
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function setNewTimeout(
        ProcessInterface $process,
        StateMachineItemTransfer $stateMachineItemTransfer,
        DateTime $currentTime
    );

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param string $stateId
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function dropOldTimeout(
        ProcessInterface $process,
        $stateId,
        StateMachineItemTransfer $stateMachineItemTransfer
    );

}
