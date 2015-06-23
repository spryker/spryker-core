<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

class SprykerFeature_Zed_Sales_Business_Model_History
{

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderHistory
     */
    public function getHistoryEntity()
    {
        return new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderHistory();
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderHistory
     */
    public function createHistoryEntry(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity)
    {
        $historyEntity = $this->getHistoryEntity();
        $historyEntity->setOrder($orderEntity);
        $historyEntity->setEmail($orderEntity->getEmail());
        $historyEntity->setSalutation($orderEntity->getSalutation());
        $historyEntity->setFirstName($orderEntity->getFirstName());
        $historyEntity->setLastName($orderEntity->getLastName());
        $historyEntity->save();

        return $historyEntity;
    }

}
