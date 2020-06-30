<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
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
            ->leftJoinWithLocale()
            ->findOne();

        if ($orderEntity === null) {
            return null;
        }

        $orderTransfer = $this->orderHydrator
            ->hydrateOrderTransferFromPersistenceBySalesOrder($orderEntity);

        $orderTransfer = $this->expandWithLocale($orderTransfer, $orderEntity);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandWithLocale(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity): OrderTransfer
    {
        if (!$orderEntity->getLocale()) {
            return $orderTransfer;
        }
        $localeTransfer = (new LocaleTransfer())
            ->fromArray($orderEntity->getLocale()->toArray(), true);

        return $orderTransfer->setLocale($localeTransfer);
    }
}
