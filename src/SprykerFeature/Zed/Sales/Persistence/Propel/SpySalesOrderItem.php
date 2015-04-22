<?php

namespace SprykerFeature\Zed\Sales\Persistence\Propel;

use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusHistory;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusHistoryQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderItem as BaseSpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderItemTableMap;
use SprykerFeature\Zed\Salesrule\Business\Model\DiscountableItemInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order_item' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpySalesOrderItem extends BaseSpySalesOrderItem implements DiscountableItemInterface
{
    public function preSave(ConnectionInterface $con = null)
    {
        $this->updateHistory();
        $this->updateLastStatusChangeDate();
        return true;
    }

    protected function updateHistory()
    {
        $statusChanged = $this->isColumnModified(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATUS);

        if ($statusChanged) {
            $entity = new SpyOmsOrderItemStatusHistory();
            $entity->setFkOmsOrderItemStatus($this->getFkOmsOrderItemStatus());
            $date = new \DateTime();
            $entity->setCreatedAt($date);
            $entity->setUpdatedAt($date);
            $entity->setOrderItem($this); // saved by automatic-cascade !
        }
    }

    /**
     * @param string $format
     * @return \DateTime|null
     */
    public function getLastStatusChange($format = 'Y-m-d H:i:s')
    {
        $lastStatusChange = parent::getLastStatusChange();
        if (isset($lastStatusChange)) {
            if (is_null($format)) {
                return new \DateTime($lastStatusChange);
            } else {
                return $lastStatusChange;
            }
        }

        $item = SpyOmsOrderItemStatusHistoryQuery::create()
            ->orderByCreatedAt(Criteria::DESC)
            ->findOneByFkSalesOrderItem($this->getIdSalesOrderItem());

        if (isset($item)) {
            return $item->getCreatedAt($format);
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isBundle()
    {
        return (null !== $this->fk_sales_order_item_bundle);
    }

    protected function updateLastStatusChangeDate()
    {
        $statusChanged = $this->isColumnModified(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATUS);
        if ($statusChanged) {
            $this->setLastStatusChange(new \DateTime());
        }
    }
}
