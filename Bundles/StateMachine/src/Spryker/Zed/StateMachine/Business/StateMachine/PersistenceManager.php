<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;

class PersistenceManager implements PersistenceManagerInterface
{

    /**
     * @var SpyStateMachineItemState[]
     */
    protected static $stateEntityBuffer = [];

    /**
     * @var SpyStateMachineProcess[]
     */
    protected static $processEntityBuffer = [];

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return SpyStateMachineItemState
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStateMachineItemStateEntity($stateName, $idStateMachineProcess)
    {
        if (array_key_exists($stateName, self::$stateEntityBuffer)) {
            return self::$stateEntityBuffer[$stateName];
        }

        $stateMachineItemStateEntity = SpyStateMachineItemStateQuery::create()->findOneByName($stateName);

        if (!isset($stateMachineItemStateEntity)) {
            $stateMachineItemStateEntity = new SpyStateMachineItemState();
            $stateMachineItemStateEntity->setName($stateName);
            $stateMachineItemStateEntity->setFkStateMachineProcess($idStateMachineProcess);
            $stateMachineItemStateEntity->save();
        }

        $stateBuffer[$stateName] = $stateMachineItemStateEntity;

        return $stateMachineItemStateEntity;
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        if (array_key_exists($stateMachineProcessTransfer->getProcessName(), self::$processEntityBuffer)) {
            return self::$processEntityBuffer[$stateMachineProcessTransfer->getProcessName()];
        }

        $stateMachineProcessEntity = SpyStateMachineProcessQuery::create()->findOneByName(
            $stateMachineProcessTransfer->getProcessName()
        );

        if (!isset($stateMachineProcessEntity)) {
            $stateMachineProcessEntity = new SpyStateMachineProcess();
            $stateMachineProcessEntity->setName($stateMachineProcessTransfer->getProcessName());
            $stateMachineProcessEntity->setStateMachineName($stateMachineProcessTransfer->getStateMachineName());
            $stateMachineProcessEntity->save();
        }

        $processBuffer[$stateMachineProcessTransfer->getProcessName()] = $stateMachineProcessEntity;

        return $stateMachineProcessEntity->getIdStateMachineProcess();
    }

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return int
     */
    public function getInitialStateIdByStateName($stateName, $idStateMachineProcess)
    {
        $stateMachineItemStateEntity = $this->getStateMachineItemStateEntity($stateName, $idStateMachineProcess);

        return $stateMachineItemStateEntity->getIdStateMachineItemState();
    }
}
