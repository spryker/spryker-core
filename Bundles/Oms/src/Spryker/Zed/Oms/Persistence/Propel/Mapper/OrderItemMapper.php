<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemStateTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Propel\Runtime\Collection\ObjectCollection;
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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[] $omsOrderItemStateHistoryEntities
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[][]
     */
    public function mapOmsOrderItemStateHistoryEntityCollectionToItemStateHistoryTransfers(
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
}
