<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Model\Order\OrderReader as OrderReaderWithoutMultiShippingAddress;

class OrderReader extends OrderReaderWithoutMultiShippingAddress
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetailsWithoutShippingAddress($idSalesOrder)
            ->findOne();

        if ($orderEntity === null) {
            return null;
        }

        return $this->orderHydrator
            ->hydrateOrderTransferFromPersistenceBySalesOrder($orderEntity);
    }
}
