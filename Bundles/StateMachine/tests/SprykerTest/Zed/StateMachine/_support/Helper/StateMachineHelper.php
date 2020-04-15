<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StateMachineItemBuilder;
use Generated\Shared\DataBuilder\StateMachineProcessBuilder;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class StateMachineHelper extends Module
{
    use DataCleanupHelperTrait;

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

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess $stateMachineProcessEntity
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    public function createStateMachineItemState(SpyStateMachineProcess $stateMachineProcessEntity): SpyStateMachineItemState
    {
        $stateMachineItemStateTransfer = (new StateMachineItemBuilder())->build();

        $stateMachineItemStateEntity = $this->createStateMachineItemStatePropelEntity();
        $stateMachineItemStateEntity->setName($stateMachineItemStateTransfer->getEventName());
        $stateMachineItemStateEntity->setFkStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());

        $stateMachineItemStateEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($stateMachineItemStateEntity): void {
            $stateMachineItemStateEntity->delete();
        });

        return $stateMachineItemStateEntity;
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    protected function createStateMachineItemStatePropelEntity(): SpyStateMachineItemState
    {
        return new SpyStateMachineItemState();
    }
}
