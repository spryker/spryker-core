<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Shared\Oms\OmsConstants;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;

class PersistenceManager implements PersistenceManagerInterface
{

    protected static $stateEntityBuffer = [];

    protected static $processEntityBuffer = [];

    /**
     * @param string $stateName
     *
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @throws \Propel\Runtime\Exception\PropelException
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
