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
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class OrdersResourceMapper implements OrdersResourceMapperInterface
{
    protected const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderItemsTransfer[] $orderItemTransfers
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttributes(OrderTransfer $orderTransfer, array $orderItemTransfers): OrdersRestAttributesTransfer
    {
        $ordersRestAttributesTransfer = (new OrdersRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $ordersRestAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        $ordersRestAttributesTransfer->setItems(new ArrayObject());

        foreach ($orderItemTransfers as $orderItem) {
            $ordersRestAttributesTransfer->addItems(
                (new OrderItemsRestAttributesTransfer())->fromArray($orderItem->toArray(), true)
            );
        }

        return $ordersRestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\OrderItemsTransfer[]
     */
    public function mapTransformedBundleItems(OrderTransfer $orderTransfer, array $orderItems): array
    {
        $transformedItemTransfers = [];

        foreach ($orderItems as $orderItem) {
            if ($orderItem instanceof AbstractTransfer) {
                $transformedItemTransfers[] = $orderItem;

                continue;
            }

            $transformedItemTransfers[] = $orderItem[static::BUNDLE_PRODUCT];
        }

        return $transformedItemTransfers;
    }
}
