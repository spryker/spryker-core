<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderItemMapper implements SalesOrderItemMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function mapSpySalesOrderItemEntityToSalesOrderItemEntity(
        SpySalesOrderItem $salesOrderItemEntity,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $salesOrderItemEntityTransfer->fromArray($salesOrderItemEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function mapSalesOrderItemEntityToSpySalesOrderItemEntity(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        SpySalesOrderItem $salesOrderItem
    ): SpySalesOrderItem {
        $salesOrderItem->fromArray($salesOrderItemEntity->toArray(true));

        return $salesOrderItem;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItemEntities
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function mapSalesOrderItemEntityCollectionToOrderItemTransfers(ObjectCollection $salesOrderItemEntities): array
    {
        $itemTransfers = [];

        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity */
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfers[] = (new ItemTransfer())
                ->fromArray($salesOrderItemEntity->toArray(), true)
                ->setProcess($salesOrderItemEntity->getProcess()->getName())
                ->setState($this->mapSalesOrderItemEntityToItemStateTransfer($salesOrderItemEntity, new ItemStateTransfer()));
        }

        return $itemTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $omsOrderItemStateHistoryEntities
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[][]
     */
    public function mapStateHistoryEntityCollectionToItemStateHistoryTransfers(
        ObjectCollection $omsOrderItemStateHistoryEntities
    ): array {
        $itemStateTransfers = [];

        foreach ($omsOrderItemStateHistoryEntities as $omsOrderItemStateHistory) {
            $itemStateTransfers[$omsOrderItemStateHistory->getFkSalesOrderItem()][] = (new ItemStateTransfer())
                ->fromArray($omsOrderItemStateHistory->toArray(), true)
                ->setName($omsOrderItemStateHistory->getState()->getName())
                ->setIdSalesOrder($omsOrderItemStateHistory->getOrderItem()->getFkSalesOrder());
        }

        return $itemStateTransfers;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemStateTransfer $itemStateTransfer
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer
     */
    protected function mapSalesOrderItemEntityToItemStateTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        ItemStateTransfer $itemStateTransfer
    ): ItemStateTransfer {
        $itemStateTransfer = $itemStateTransfer
            ->fromArray($salesOrderItemEntity->getState()->toArray(), true)
            ->setIdSalesOrder($salesOrderItemEntity->getFkSalesOrder());

        $latestOrderItemStateHistoryEntity = $this->extractLatestOrderItemStateHistory($salesOrderItemEntity);

        if ($latestOrderItemStateHistoryEntity) {
            $itemStateTransfer->setCreatedAt($latestOrderItemStateHistoryEntity->getCreatedAt());
        }

        return $itemStateTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory|null
     */
    protected function extractLatestOrderItemStateHistory(SpySalesOrderItem $salesOrderItemEntity): ?SpyOmsOrderItemStateHistory
    {
        $latestOrderItemStateHistoryEntity = $salesOrderItemEntity->getStateHistories()->getIterator()->current();

        foreach ($salesOrderItemEntity->getStateHistories() as $omsOrderItemStateHistory) {
            if ($omsOrderItemStateHistory->getIdOmsOrderItemStateHistory() > $latestOrderItemStateHistoryEntity->getIdOmsOrderItemStateHistory()) {
                $latestOrderItemStateHistoryEntity = $omsOrderItemStateHistory;
            }
        }

        return $latestOrderItemStateHistoryEntity;
    }
}
