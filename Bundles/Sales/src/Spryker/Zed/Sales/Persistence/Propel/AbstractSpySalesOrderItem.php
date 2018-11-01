<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem as BaseSpySalesOrderItem;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order_item' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AbstractSpySalesOrderItem extends BaseSpySalesOrderItem
{
    /**
     * @var bool
     */
    protected $statusChanged = false;

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null)
    {
        $this->statusChanged = array_key_exists(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE, $this->modifiedColumns);

        return true;
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null)
    {
        if ($this->statusChanged) {
            // FIXME Wrong dependency direction
            $omsOrderItemStateHistoryEntity = $this->createOmsOrderItemStateHistoryEntity();
            $omsOrderItemStateHistoryEntity->setOrderItem($this);
            $omsOrderItemStateHistoryEntity->setState($this->getState());
            $omsOrderItemStateHistoryEntity->save();
        }
        $this->statusChanged = false;
    }

    /**
     * @return void
     */
    public function clear()
    {
        parent::clear();

        $this->statusChanged = false;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory
     */
    protected function createOmsOrderItemStateHistoryEntity()
    {
        return new SpyOmsOrderItemStateHistory();
    }
}
