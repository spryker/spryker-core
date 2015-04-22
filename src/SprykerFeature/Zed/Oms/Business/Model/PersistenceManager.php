<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerFeature\Zed\Oms\Business\OmsSettings;

class PersistenceManager implements PersistenceManagerInterface
{

    protected static $statusEntityBuffer = array();
    protected static $processEntityBuffer = array();

    /**
     * @param string $statusName
     * @return \SprykerFeature_Zed_Oms_Persistence_SpyOmsOrderItemStatus
     */
    public function getStatusEntity($statusName)
    {
        if (array_key_exists($statusName, self::$statusEntityBuffer)) {
            return self::$statusEntityBuffer[$statusName];
        }

        $statusEntity = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusQuery::create()->findOneByName($statusName);

        if (!isset($statusEntity)) {
            $statusEntity = new \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus();
            $statusEntity->setName($statusName);
            $statusEntity->save();
        }

        $statusBuffer[$statusName] = $statusEntity;

        return $statusEntity;
    }

    /**
     * @param string $processName
     * @return \SprykerFeature_Zed_Oms_Persistence_SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        if (array_key_exists($processName, self::$processEntityBuffer)) {
            return self::$processEntityBuffer[$processName];
        }

        $processEntity = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery::create()->findOneByName($processName);

        if (!isset($processEntity)) {
            $processEntity = new \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess();
            $processEntity->setName($processName);
            $processEntity->save();
        }

        $processBuffer[$processName] = $processEntity;

        return $processEntity;

    }

    /**
     * @return \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus
     */
    public function getInitialStatusEntity()
    {
        return $this->getStatusEntity(OmsSettings::INITIAL_STATUS);
    }

}
