<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;

class StateMachineMapper
{
    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess $stateMachineProcess
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    public function mapStateMachineProcessEntityToStateMachineProcessTransfer(
        SpyStateMachineProcess $stateMachineProcess,
        StateMachineProcessTransfer $stateMachineProcessTransfer
    ): StateMachineProcessTransfer {
        $stateMachineProcessTransfer->fromArray($stateMachineProcess->toArray(), true)
            ->setProcessName($stateMachineProcess->getName());

        return $stateMachineProcessTransfer;
    }
}
