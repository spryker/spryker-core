<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $salesOrderEntityCollection
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapSalesOrderEntityCollectionToOrderTransfers(
        Collection $salesOrderEntityCollection,
        ArrayObject $orderTransfers
    ): ArrayObject {
        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);

            $orderTransfers->offsetSet($orderTransfer->getIdSalesOrder(), $orderTransfer);
        }

        return $orderTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntityCollection
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapSalesOrderItemEntityCollectionToOrderTransfers(
        ObjectCollection $salesOrderItemEntityCollection,
        ArrayObject $orderTransfers
    ): ArrayObject {
        foreach ($salesOrderItemEntityCollection as $salesOrderItemEntity) {
            $idSalesOrder = $salesOrderItemEntity->getFkSalesOrder();

            if ($orderTransfers->offsetExists($idSalesOrder)) {
                /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
                $orderTransfer = $orderTransfers->offsetGet($idSalesOrder);

                $orderTransfer->addItem(
                    (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true)
                );
            }
        }

        return $orderTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals[] $salesOrderTotalsEntityCollection
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapSalesOrderTotalsEntityCollectionToOrderTransfers(
        ObjectCollection $salesOrderTotalsEntityCollection,
        ArrayObject $orderTransfers
    ): ArrayObject {
        $salesOrderTotalsEntities = $this->indexSalesOrderTotalsEntitiesByIdSalesOrder($salesOrderTotalsEntityCollection);

        foreach ($orderTransfers as $orderTransfer) {
            $salesOrderTotalsEntity = $salesOrderTotalsEntities[$orderTransfer->getIdSalesOrder()] ?? null;

            if ($salesOrderTotalsEntity) {
                $orderTransfer->setTotals(
                    (new TotalsTransfer())->fromArray($salesOrderTotalsEntity->toArray(), true)
                );
            }
        }

        return $orderTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals[] $salesOrderTotalsEntityCollection
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals[]
     */
    protected function indexSalesOrderTotalsEntitiesByIdSalesOrder(ObjectCollection $salesOrderTotalsEntityCollection): array
    {
        $salesOrderTotalsEntities = [];

        foreach ($salesOrderTotalsEntityCollection as $salesOrderTotalsEntity) {
            $idSalesOrder = $salesOrderTotalsEntity->getFkSalesOrder();

            if (!isset($salesOrderTotalsEntities[$idSalesOrder])) {
                $salesOrderTotalsEntities[$idSalesOrder] = $salesOrderTotalsEntity;
            }
        }

        return $salesOrderTotalsEntities;
    }
}
