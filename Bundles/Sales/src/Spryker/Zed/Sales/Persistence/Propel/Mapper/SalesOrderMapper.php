<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $salesOrderEntityCollection
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderEntityCollectionToOrderListTransfer(
        ObjectCollection $salesOrderEntityCollection,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);

            $orderTransfer = $this->mapSalesOrderItemEntityCollectionToOrderTransfer(
                $salesOrderEntity->getItems(),
                $orderTransfer
            );

            $salesOrderTotalsEntity = $salesOrderEntity->getLastOrderTotals();

            if ($salesOrderTotalsEntity) {
                $orderTransfer = $this->mapSalesOrderTotalsEntityToOrderTransfer(
                    $salesOrderTotalsEntity,
                    $orderTransfer
                );
            }

            $orderListTransfer->addOrder($orderTransfer);
        }

        return $orderListTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderTotals $salesOrderTotalsEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderTotalsEntityToOrderTransfer(
        SpySalesOrderTotals $salesOrderTotalsEntity,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal($salesOrderTotalsEntity->getGrandTotal());
        $orderTransfer->setTotals($totalsTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntityCollection
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderItemEntityCollectionToOrderTransfer(
        ObjectCollection $salesOrderItemEntityCollection,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        foreach ($salesOrderItemEntityCollection as $salesOrderItemEntity) {
            $orderTransfer->addItem(
                (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true)
            );
        }

        return $orderTransfer;
    }
}
