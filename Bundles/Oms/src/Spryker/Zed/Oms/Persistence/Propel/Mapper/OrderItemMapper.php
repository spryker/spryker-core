<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
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
     * @param array $orderItemEntityArray
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapOrderItemEntityArrayToItemTransfer(array $orderItemEntityArray, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $itemTransfer
            ->setProcess($orderItemEntityArray[SpyOmsOrderProcessTableMap::COL_NAME])
            ->setState((new ItemStateTransfer())->setName($orderItemEntityArray[SpyOmsOrderItemStateTableMap::COL_NAME]))
            ->setSku($orderItemEntityArray[SpySalesOrderItemTableMap::COL_SKU])
            ->setQuantity($orderItemEntityArray[SpySalesOrderItemTableMap::COL_QUANTITY]);
    }
}
