<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderItemsRestAttributesTransfer;
use Generated\Shared\Transfer\OrderRestAttributesTransfer;
use Generated\Shared\Transfer\OrdersRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class OrdersResourceMapper implements OrdersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\OrdersRestAttributesTransfer $ordersRestAttributes
     *
     * @return void
     */
    public function mapOrderListToOrdersRestAttribute(OrderTransfer $orderTransfer, array $items, OrdersRestAttributesTransfer $ordersRestAttributes): void
    {
        $ordersRestAttributes->addOrders($this->mapOrderToOrdersRestAttribute($orderTransfer, $items));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\OrderRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttribute(OrderTransfer $orderTransfer, array $items): OrderRestAttributesTransfer
    {
        $taxAmount = $orderTransfer->getTotals()->getTaxTotal()->getAmount();

        $orderTransfer->setItems(new ArrayObject());
        $orderTransfer->setTotals($orderTransfer->getTotals()->setTaxTotal(null));
        $orderRestAttributesTransfer = (new OrderRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $orderRestAttributesTransfer->setTotals($orderRestAttributesTransfer->getTotals()->setTaxTotal($taxAmount));

        foreach ($items as $item) {
            if ($item instanceof AbstractTransfer) {
                $orderRestAttributesTransfer->addItems(
                    (new OrderItemsRestAttributesTransfer())->fromArray($item->toArray(), true)
                );

                continue;
            }

            $orderRestAttributesTransfer->addItems((new OrderItemsRestAttributesTransfer())->fromArray(
                $item[ProductBundleGrouper::BUNDLE_PRODUCT]->toArray(),
                true
            ));
        }

        return $orderRestAttributesTransfer;
    }
}
