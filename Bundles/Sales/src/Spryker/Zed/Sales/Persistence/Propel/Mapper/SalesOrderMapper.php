<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[] $orderEntities
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderEntitiesToOrderListTransfer(array $orderEntities, OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        foreach ($orderEntities as $orderEntity) {
            $orderListTransfer->addOrder(
                (new OrderTransfer())->fromArray($orderEntity->toArray(), true)
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $salesOrderEntityCollection
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapSalesOrderEntityCollectionToOrderTransfers(Collection $salesOrderEntityCollection): array
    {
        $orderTransfers = [];

        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfers[] = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        }

        return $orderTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals[] $salesOrderTotalsEntityCollection
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer[]
     */
    public function mapSalesOrderTotalsEntityCollectionToMappedOrderTotalsByIdSalesOrder(
        ObjectCollection $salesOrderTotalsEntityCollection
    ): array {
        $mappedTotalsTransfers = [];

        foreach ($salesOrderTotalsEntityCollection as $salesOrderTotalsEntity) {
            $mappedTotalsTransfers[$salesOrderTotalsEntity->getFkSalesOrder()] = (new TotalsTransfer())->fromArray($salesOrderTotalsEntity->toArray(), true);
        }

        return $mappedTotalsTransfers;
    }
}
