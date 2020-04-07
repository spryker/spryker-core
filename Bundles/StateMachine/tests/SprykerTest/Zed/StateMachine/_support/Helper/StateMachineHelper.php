<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StateMachineProcessBuilder;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;

class StateMachineHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess
     */
    public function haveStateMachineProcess(array $seedData = []): SpyStateMachineProcess
    {
        $stateMachineProcessTransfer = (new StateMachineProcessBuilder($seedData))->build();

        $stateMachineProcessEntity = $this->createStateMachineProcessPropelEntity();
        $stateMachineProcessEntity->setName($stateMachineProcessTransfer->getProcessName());
        $stateMachineProcessEntity->setStateMachineName($stateMachineProcessTransfer->getStateMachineName());

        $stateMachineProcessEntity->save();

        return $stateMachineProcessEntity;
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess
     */
    protected function createStateMachineProcessPropelEntity(): SpyStateMachineProcess
    {
        return new SpyStateMachineProcess();
    }
}
