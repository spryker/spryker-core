<?php

class SprykerFeature_Zed_Sales_Business_Model_Item
{

    /**
     * @var \SprykerFeature_Zed_Library_Lock_Factory
     */
    protected static $lockFactory;

    /**
     * @param array $itemIds
     * @return PropelCollection
     */
    public function getOrderItemsByIds(array $itemIds)
    {
        $orderItemQuery = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery();
        $orderItemCollection = $orderItemQuery->filterByIdSalesOrderItem($itemIds)
                                              ->find();
        return $orderItemCollection;
    }

    public function getStatusCount($sku)
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create()
            ->joinStatus()
            ->findBySku($sku);
    }

    /**
     * @param $idSalesOrder
     * @return array
     */
    public function getStatusHistoryWithStatusAndItemSkuByOrderId($idSalesOrder)
    {
        $orderItems = $this->factory->createModelOrder()->getOrderBySalesOrderId($idSalesOrder)->getItems();
        $orderItemIds = $this->extractOrderItemIds($orderItems);

        $statusHistory = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusHistoryQuery::create()
            ->joinOrderItem()
            ->joinStatus()
            ->filterBy('FkSalesOrderItem', $orderItemIds, Criteria::IN)
            ->addDescendingOrderByColumn(\SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsOrderItemStatusHistoryTableMap::COL_ID_SALES_ORDER_ITEM_STATUS_HISTORY)
            ->find();
        return $this->getStatusHistoryWithStatusAndItemSku($statusHistory);
    }

    /**
     * @param $orderItems
     * @return array
     */
    protected function extractOrderItemIds(PropelObjectCollection $orderItems)
    {
        $ids = array();
        /* @var $orderItem \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem */
        foreach ($orderItems as $orderItem) {
            $ids[] = $orderItem->getPrimaryKey();
        }
        return $ids;
    }

    /**
     * @param $statusHistory
     * @return array
     */
    protected function getStatusHistoryWithStatusAndItemSku(PropelObjectCollection $statusHistory)
    {
        $historyWithStatusAndItemSku = array();
        /* @var $statusRow \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusHistory */
        foreach ($statusHistory as $statusRow) {

            $sku = $statusRow->getOrderItem()->getSku();
            $historyWithStatusAndItemSku[$statusRow->getFkSalesOrderItem()][] = array(
                'id_sales_order_item' => $statusRow->getFkSalesOrderItem(),
                'sku' => $sku,
                'status' => $statusRow->getStatus()->getName(),
                'created_at' => $statusRow->getCreatedAt(),
                'updated_at' => $statusRow->getUpdatedAt(),
            );
        }
        return $historyWithStatusAndItemSku;
    }

    /**
     * @return \SprykerFeature_Zed_Library_Lock_Factory
     */
    protected function getLockFactory()
    {
        if (!self::$lockFactory) {
            $dateInterval = DateInterval::createFromDateString(\SprykerFeature_Zed_Sales_Business_Interface_LockConstant::LOCK_EXPIRE_INTERVAL_AS_STRING);
            self::$lockFactory = $this->facadeMisc->getLockFactory(\SprykerFeature_Zed_Sales_Business_Interface_LockConstant::LOCK_RESOURCE_NAME, $dateInterval);
        }
        return self::$lockFactory;
    }

    /**
     * @param int $orderItemId
     */
    public function createLock($orderItemId)
    {
        return $this->getLockFactory()->createLock($orderItemId);
    }

    /**
     * @param int $orderItemId
     * @return bool
     */
    public function isLocked($orderItemId)
    {
        return $this->getLockFactory()->isLocked($orderItemId);
    }

    public function getSimpleItemStatusOverviewIncludingIds($processId = null)
    {
        $processWhere = null === $processId ? '' : ' AND so.fk_sales_order_process = ' . $processId;
        $sql = "SELECT
        spy_sales_order_item_status.id_sales_order_item_status AS status_id,
        spy_sales_order_item_status.name AS status_name,
    count(distinct fk_sales_order) AS order_count,
    count(id_sales_order_item) AS item_count
        FROM spy_sales_order_item AS soi
        INNER JOIN spy_sales_order_item_status ON id_sales_order_item_status = FK_OMS_ORDER_ITEM_STATUS
        AND EXISTS (SELECT 1 FROM spy_sales_order as so WHERE soi.fk_sales_order = so.id_sales_order  AND so.is_test = 0" . $processWhere . ")
        GROUP BY id_sales_order_item_status;";
        $connection = Propel::getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        usort($list, array($this, 'idOverviewSortCallback'));
        return $list;
    }

    protected function idOverviewSortCallback($a, $b)
    {
        $tmp_array = array($a['status_name'], $b['status_name']);
        sort($tmp_array, SORT_STRING);
        if ($tmp_array[0] == $a['status_name']) {
            return -1;
        } else {
            return 1;
        }
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param $toBeCaptured
     * @return bool
     * @throws \Exception
     */
    public function setItemToBeCaptured(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, $toBeCaptured)
    {
        if ($orderItem->getToBeCaptured() === $toBeCaptured) {
            throw new \Exception('Attempt to set to_be_captured twice! order-id: ' . $orderItem->getFkSalesOrder());
        }
        //FIXME check what happens if set false to true !!!
        $orderItem->setToBeCaptured($toBeCaptured);
        $orderItem->save();
        return true;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param $toBeBilled
     * @return bool
     * @throws \Exception
     */
    public function setItemToBeBilled(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, $toBeBilled)
    {
        if ($orderItem->getToBeBilled() === $toBeBilled) {
            throw new \Exception('Attempt to set to_be_billed twice! order-id: ' . $orderItem->getFkSalesOrder());
        }
        //FIXME check what happens if set false to true !!!
        $orderItem->setToBeBilled($toBeBilled);
        $orderItem->save();
        return true;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @return bool
     */
    public function isItemToBeBilled(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        if (is_null($orderItem->getToBeBilled())) {
            return false;
        } else {
            return $orderItem->getToBeBilled();
        }
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @return bool
     */
    public function isItemToBeCaptured(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        if (is_null($orderItem->getToBeCaptured())) {
            return false;
        } else {
            return $orderItem->getToBeCaptured();
        }
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItemEntity
     * @return bool
     */
    public function isBillable(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItemEntity)
    {
        return (bool) $orderItemEntity->getToBeBilled();
    }

}
