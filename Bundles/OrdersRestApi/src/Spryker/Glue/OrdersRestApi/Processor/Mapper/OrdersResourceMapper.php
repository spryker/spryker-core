<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderItemsRestAttributesTransfer;
use Generated\Shared\Transfer\OrdersRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrdersResourceMapper implements OrdersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttributes(OrderTransfer $orderTransfer): OrdersRestAttributesTransfer
    {
        $ordersRestAttributesTransfer = (new OrdersRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $ordersRestAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        $ordersRestAttributesTransfer->setItems(new ArrayObject());

        foreach ($orderTransfer->getItems() as $orderItem) {
            $ordersRestAttributesTransfer->addItems(
                (new OrderItemsRestAttributesTransfer())->fromArray($orderItem->toArray(), true)
            );
        }

        return $ordersRestAttributesTransfer;
    }
}
