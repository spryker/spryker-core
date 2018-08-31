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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttribute(OrderTransfer $orderTransfer, array $items): OrdersRestAttributesTransfer
    {
        $ordersRestAttributesTransfer = (new OrdersRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $ordersRestAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        $ordersRestAttributesTransfer->setItems(new ArrayObject());

        foreach ($items as $item) {
            $ordersRestAttributesTransfer->addItems(
                (new OrderItemsRestAttributesTransfer())->fromArray($item->toArray(), true)
            );
        }

        return $ordersRestAttributesTransfer;
    }
}
