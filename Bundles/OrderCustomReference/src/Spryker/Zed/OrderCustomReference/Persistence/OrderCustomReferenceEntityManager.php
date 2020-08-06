<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class OrderCustomReferenceEntityManager extends AbstractEntityManager implements OrderCustomReferenceEntityManagerInterface
{
    protected const COLUMN_ORDER_CUSTOM_REFERENCE = 'OrderCustomReference';

    /**
     * @module Sales
     *
     * @param int $idSalesOrder
     * @param string $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReference(int $idSalesOrder, string $orderCustomReference): OrderCustomReferenceResponseTransfer
    {
        $orderCustomReferenceResponseTransfer = (new OrderCustomReferenceResponseTransfer())->setIsSuccessful(true);

        $salesOrderQuery = (new SpySalesOrderQuery())->filterByIdSalesOrder($idSalesOrder);

        if (!$salesOrderQuery->findOne()) {
            return $orderCustomReferenceResponseTransfer->setIsSuccessful(false);
        }

        if ($salesOrderQuery->update([static::COLUMN_ORDER_CUSTOM_REFERENCE => $orderCustomReference])) {
            return $orderCustomReferenceResponseTransfer;
        }

        return $orderCustomReferenceResponseTransfer->setIsSuccessful(false);
    }
}
