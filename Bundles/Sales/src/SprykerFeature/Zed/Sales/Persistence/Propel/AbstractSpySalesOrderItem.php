<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence\Propel;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Propel\Runtime\Connection\ConnectionInterface;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem as BaseSpySalesOrderItem;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order_item' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AbstractSpySalesOrderItem extends BaseSpySalesOrderItem
{

    /**
     * @var bool
     */
    protected $statusChanged = false;

    /**
     * @param ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(ConnectionInterface $con = null)
    {
        $this->statusChanged = in_array(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE, $this->modifiedColumns);

        return true;
    }

    /**
     * @param ConnectionInterface|null $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * 
     * @return void
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if ($this->statusChanged) {
            // FIXME Wrong dependency direction
            $e = new SpyOmsOrderItemStateHistory();
            $e->setOrderItem($this);
            $e->setState($this->getState());
            $e->save();
        }
        $this->statusChanged = false;
    }

}
