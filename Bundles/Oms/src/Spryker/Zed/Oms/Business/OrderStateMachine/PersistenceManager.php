<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Spryker\Shared\Oms\OmsConstants;

class PersistenceManager implements PersistenceManagerInterface
{

    protected static $stateEntityBuffer = [];

    protected static $processEntityBuffer = [];

    /**
     * @param string $stateName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getStateEntity($stateName)
    {
        if (array_key_exists($stateName, self::$stateEntityBuffer)) {
            return self::$stateEntityBuffer[$stateName];
        }

        $stateEntity = SpyOmsOrderItemStateQuery::create()->findOneByName($stateName);

        if (!isset($stateEntity)) {
            $stateEntity = new SpyOmsOrderItemState();
            $stateEntity->setName($stateName);
            $stateEntity->save();
        }

        $stateBuffer[$stateName] = $stateEntity;

        return $stateEntity;
    }

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        if (array_key_exists($processName, self::$processEntityBuffer)) {
            return self::$processEntityBuffer[$processName];
        }

        $processEntity = SpyOmsOrderProcessQuery::create()->findOneByName($processName);

        if (!isset($processEntity)) {
            $processEntity = new SpyOmsOrderProcess();
            $processEntity->setName($processName);
            $processEntity->save();
        }

        $processBuffer[$processName] = $processEntity;

        return $processEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getStateEntity(OmsConstants::INITIAL_STATUS);
    }

}
