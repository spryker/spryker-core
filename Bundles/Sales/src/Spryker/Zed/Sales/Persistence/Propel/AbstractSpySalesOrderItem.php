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
use Spryker\Zed\Propel\Persistence\BatchEntityPostSaveInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\CascadeActiveRecordBatchProcessorTrait;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order_item' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AbstractSpySalesOrderItem extends BaseSpySalesOrderItem implements BatchEntityPostSaveInterface
{
    use CascadeActiveRecordBatchProcessorTrait {
        persist as protected;
        commit as protected;
        commitIdentical as protected;
    }

    /**
     * @var bool
     */
    protected bool $statusChanged = false;

    /**
     * @return void
     */
    public function setStatusChanged(): void
    {
        $this->statusChanged = true;
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        $this->statusChanged = array_key_exists(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE, $this->modifiedColumns);

        return true;
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
        // This code originates from the Spryker Propel UuidBehavior module and should only be executed when the behavior is enabled in the project configuration.
        if (method_exists($this, 'updateUuidBeforeUpdate')) {
            $this->updateUuidBeforeUpdate();
        }

        return parent::preUpdate($con);
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
        if ($this->statusChanged && $this->getIdSalesOrderItem()) {
            /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity */
            $salesOrderItemEntity = $this;
            // FIXME Wrong dependency direction
            $omsOrderItemStateHistoryEntity = $this->createOmsOrderItemStateHistoryEntity();
            $omsOrderItemStateHistoryEntity->setOrderItem($salesOrderItemEntity);
            $omsOrderItemStateHistoryEntity->setState($this->getState());
            $omsOrderItemStateHistoryEntity->save();
        }
        $this->statusChanged = false;
    }

    /**
     * @return void
     */
    public function batchPostSave(): void
    {
        if ($this->statusChanged && $this->getIdSalesOrderItem()) {
            /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity */
            $salesOrderItemEntity = $this;
            $omsOrderItemStateHistoryEntity = $this->createOmsOrderItemStateHistoryEntity();
            $omsOrderItemStateHistoryEntity->setOrderItem($salesOrderItemEntity);
            $omsOrderItemStateHistoryEntity->setFkOmsOrderItemState($this->getFkOmsOrderItemState());

            $this->sharedPersist($omsOrderItemStateHistoryEntity);
        }

        $this->statusChanged = false;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        parent::clear();

        $this->statusChanged = false;

        return $this;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory
     */
    protected function createOmsOrderItemStateHistoryEntity()
    {
        return new SpyOmsOrderItemStateHistory();
    }
}
