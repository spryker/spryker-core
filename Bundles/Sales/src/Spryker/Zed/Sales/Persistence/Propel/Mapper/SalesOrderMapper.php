<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
            $orderListTransfer->addOrder(
                (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true)
            );
        }

        return $orderListTransfer;
    }
}
