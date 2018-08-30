<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderRestAttributesTransfer;
use Generated\Shared\Transfer\OrdersRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrdersResourceMapper implements OrdersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderListToOrdersRestAttribute(OrderListTransfer $orderListTransfer): OrdersRestAttributesTransfer
    {
        $ordersRestAttributes = new OrdersRestAttributesTransfer();

        foreach ($orderListTransfer->getOrders() as $order) {
            $ordersRestAttributes->addOrders((new OrderRestAttributesTransfer())->fromArray($order->toArray(), true));
        }

        return $ordersRestAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttribute(OrderTransfer $orderTransfer): OrderRestAttributesTransfer
    {
        return (new OrderRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
    }
}
