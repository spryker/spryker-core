<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderMapper
{
    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrder> $orderEntities
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderEntitiesToOrderListTransfer(array $orderEntities, OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        foreach ($orderEntities as $orderEntity) {
            $orderListTransfer->addOrder(
                (new OrderTransfer())->fromArray((array)$orderEntity->toArray(), true)
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrder> $salesOrderEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function mapSalesOrderEntityCollectionToOrderTransfers(Collection $salesOrderEntityCollection): array
    {
        $orderTransfers = [];

        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfers[] = (new OrderTransfer())->fromArray((array)$salesOrderEntity->toArray(), true);
        }

        return $orderTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderTotals> $salesOrderTotalsEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\TotalsTransfer>
     */
    public function mapSalesOrderTotalsEntityCollectionToMappedOrderTotalsByIdSalesOrder(
        ObjectCollection $salesOrderTotalsEntityCollection
    ): array {
        $mappedTotalsTransfers = [];

        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderTotals $salesOrderTotalsEntity */
        foreach ($salesOrderTotalsEntityCollection as $salesOrderTotalsEntity) {
            $totalsTransfer = (new TotalsTransfer())->fromArray((array)$salesOrderTotalsEntity->toArray(), true);
            $mappedTotalsTransfers[$salesOrderTotalsEntity->getFkSalesOrder()] = $totalsTransfer;
        }

        return $mappedTotalsTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    public function mapSalesOrderAddressEntityTransferToSalesOrderAddressEntity(
        SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer,
        SpySalesOrderAddress $salesOrderAddressEntity
    ): SpySalesOrderAddress {
        $salesOrderAddressEntity->fromArray($salesOrderAddressEntityTransfer->toArray());

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer
     */
    public function mapSalesOrderAddressEntityToSalesOrderAddressEntityTransfer(
        SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer,
        SpySalesOrderAddress $salesOrderAddressEntity
    ): SpySalesOrderAddressEntityTransfer {
        return $salesOrderAddressEntityTransfer->fromArray((array)$salesOrderAddressEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function mapSalesOrderEntityTransferToSalesOrderEntity(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        SpySalesOrder $salesOrderEntity
    ): SpySalesOrder {
        $salesOrderEntity->fromArray($salesOrderEntityTransfer->toArray());

        return $salesOrderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function mapSalesOrderEntityToSalesOrderEntityTransfer(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        SpySalesOrder $salesOrderEntity
    ): SpySalesOrderEntityTransfer {
        return $salesOrderEntityTransfer->fromArray((array)$salesOrderEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderEntityToSalesOrderTransfer(
        SpySalesOrder $salesOrderEntity,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $orderTransfer->fromArray((array)$salesOrderEntity->toArray(), true);

        return $orderTransfer;
    }
}
