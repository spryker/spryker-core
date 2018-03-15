<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderListFilterPluginInterface;

class IsOfferOrderListFilterPlugin implements OrderListFilterPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function filterOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        //TODO: clarify whether it should be done like this
        //TODO: probably move to facade method
        $orders = (array)$orderListTransfer->getOrders();
        $orders = array_filter(
            $orders,
            function (OrderTransfer $item) use ($orderListTransfer) {
                return $item->getIsOffer() === $orderListTransfer->getIsOffer();
            }
        );

        $orderListTransfer->setOrders(new ArrayObject($orders));

        return $orderListTransfer;
    }
}
