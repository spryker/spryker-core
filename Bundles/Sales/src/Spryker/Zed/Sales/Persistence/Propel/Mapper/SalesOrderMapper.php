<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $salesOrderEntityCollection
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param bool $isOrderItemsMappingEnabled
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderEntityCollectionToOrderListTransfer(
        ObjectCollection $salesOrderEntityCollection,
        OrderListTransfer $orderListTransfer,
        bool $isOrderItemsMappingEnabled
    ): OrderListTransfer {
        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);

            if ($isOrderItemsMappingEnabled) {
                $itemTransfers = $this->mapSalesOrderItemEntityCollectionToItemTransfers(
                    $salesOrderEntity->getItems(),
                    new ArrayObject()
                );

                $orderTransfer->setItems($itemTransfers);
            }

            $salesOrderTotalsEntity = $salesOrderEntity->getLastOrderTotals();

            if ($salesOrderTotalsEntity) {
                $orderTransfer->setTotals(
                    (new TotalsTransfer())->fromArray($salesOrderTotalsEntity->toArray(), true)
                );
            }

            $orderListTransfer->addOrder($orderTransfer);
        }

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntityCollection
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapSalesOrderItemEntityCollectionToItemTransfers(
        ObjectCollection $salesOrderItemEntityCollection,
        ArrayObject $itemTransfers
    ): ArrayObject {
        foreach ($salesOrderItemEntityCollection as $salesOrderItemEntity) {
            $itemTransfers->append(
                (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true)
            );
        }

        return $itemTransfers;
    }
}
