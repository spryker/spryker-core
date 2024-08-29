<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;

class OrderItemMapper implements OrderItemMapperInterface
{
    /**
     * @param array $orderItemsMatrixResult
     *
     * @return array
     */
    public function mapOrderItemMatrix(array $orderItemsMatrixResult): array
    {
        $orderItemsMatrix = [];

        foreach ($orderItemsMatrixResult as $orderItemsMatrixRow) {
            $idState = $orderItemsMatrixRow[SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE];
            $idProcess = $orderItemsMatrixRow[SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS];

            $orderItemsMatrix[$idState][$idProcess][$orderItemsMatrixRow[OmsQueryContainer::DATE_WINDOW]]
                = $orderItemsMatrixRow[OmsQueryContainer::ITEMS_COUNT];
        }

        return $orderItemsMatrix;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory> $omsOrderItemStateHistoryEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemStateTransfer>
     */
    public function mapOmsOrderItemStateHistoryEntityCollectionToItemStateHistoryTransfers(
        Collection $omsOrderItemStateHistoryEntities
    ): array {
        $itemStateTransfers = [];

        foreach ($omsOrderItemStateHistoryEntities as $omsOrderItemStateHistory) {
            $itemStateTransfers[] = (new ItemStateTransfer())
                ->fromArray($omsOrderItemStateHistory->toArray(), true)
                ->setName($omsOrderItemStateHistory->getState()->getName())
                ->setIdSalesOrderItem($omsOrderItemStateHistory->getFkSalesOrderItem())
                ->setIdSalesOrder($omsOrderItemStateHistory->getOrderItem()->getFkSalesOrder());
        }

        return $itemStateTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function mapSalesOrderItemEntityCollectionToOrderItemTransfers(
        Collection $salesOrderItemEntityCollection
    ): array {
        $itemTransfers = [];

        foreach ($salesOrderItemEntityCollection as $salesOrderItemEntity) {
            $itemTransfer = (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);

            $itemTransfer->setProcess(
                $salesOrderItemEntity->getProcess()->getName(),
            );

            $itemTransfer->setState(
                (new ItemStateTransfer())->fromArray($salesOrderItemEntity->getState()->toArray(), true),
            );

            $itemTransfers[] = $itemTransfer;
        }

        return $itemTransfers;
    }

    /**
     * @param array<array<string|int>> $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function mapSalesOrderItemEntitiesToOrderMatrixCollectionTransfer(
        array $orderItemEntities,
        OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
    ): OrderMatrixCollectionTransfer {
        foreach ($orderItemEntities as $orderItemEntity) {
            $orderMatrixTransfer = $this->mapSalesOrderItemEntityToOrderMatrixTransfer($orderItemEntity, new OrderMatrixTransfer());
            $orderMatrixCollectionTransfer->addOrderMatrix($orderMatrixTransfer);
        }

        return $orderMatrixCollectionTransfer;
    }

    /**
     * @param array<string|int> $orderItemEntity
     * @param \Generated\Shared\Transfer\OrderMatrixTransfer $orderMatrixTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixTransfer
     */
    protected function mapSalesOrderItemEntityToOrderMatrixTransfer(array $orderItemEntity, OrderMatrixTransfer $orderMatrixTransfer): OrderMatrixTransfer
    {
        return $orderMatrixTransfer->setProcessName((string)$orderItemEntity['processName'])
            ->setStateName((string)$orderItemEntity['stateName'])
            ->setDateWindow((string)$orderItemEntity['dateWindow'])
            ->setItemsCount((int)$orderItemEntity['itemsCount'])
            ->setIdProcess((int)$orderItemEntity['sub.fk_oms_order_process'])
            ->setIdState((int)$orderItemEntity['sub.fk_oms_order_item_state']);
    }
}
