<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface PersistenceManagerInterface
{

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    public function getStateMachineItemStateEntity($stateName, $idStateMachineProcess);

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer);

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return int
     */
    public function getInitialStateIdByStateName($stateName, $idStateMachineProcess);

}
