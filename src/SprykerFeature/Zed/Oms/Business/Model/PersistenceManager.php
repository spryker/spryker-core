<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusQuery;

class PersistenceManager implements PersistenceManagerInterface
{

    protected static $statusEntityBuffer = array();
    protected static $processEntityBuffer = array();

    /**
     * @param string $statusName
     * @return SpyOmsOrderItemStatus
     *
     * @throws PropelException
     */
    public function getStatusEntity($statusName)
    {
        if (array_key_exists($statusName, self::$statusEntityBuffer)) {
            return self::$statusEntityBuffer[$statusName];
        }

        $statusEntity = SpyOmsOrderItemStatusQuery::create()->findOneByName($statusName);

        if (!isset($statusEntity)) {
            $statusEntity = new SpyOmsOrderItemStatus();
            $statusEntity->setName($statusName);
            $statusEntity->save();
        }

        $statusBuffer[$statusName] = $statusEntity;

        return $statusEntity;
    }

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     * @throws PropelException
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
     * @return SpyOmsOrderItemStatus
     */
    public function getInitialStatusEntity()
    {
        return $this->getStatusEntity(OmsSettings::INITIAL_STATUS);
    }

}
