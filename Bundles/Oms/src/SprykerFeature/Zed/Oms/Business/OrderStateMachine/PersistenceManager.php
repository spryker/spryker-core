<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Oms\OmsConfig;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStateQuery;

class PersistenceManager implements PersistenceManagerInterface
{

    protected static $stateEntityBuffer = [];
    protected static $processEntityBuffer = [];

    /**
     * @param string $stateName
     *
     * @throws PropelException
     *
     * @return SpyOmsOrderItemState
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
     * @throws PropelException
     *
     * @return SpyOmsOrderProcess
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
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getStateEntity(OmsConfig::INITIAL_STATUS);
    }

}
