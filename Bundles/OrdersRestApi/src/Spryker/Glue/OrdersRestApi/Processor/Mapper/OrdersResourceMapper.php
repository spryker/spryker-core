<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderItemsRestAttributesTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderRestAttributesTransfer;
use Generated\Shared\Transfer\OrderRestExpenseAttributesTransfer;
use Generated\Shared\Transfer\OrderRestTotalAttributesTransfer;
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
            $ordersRestAttributes->addOrders(
                $orderRestAttributesTransfer = (new OrderRestAttributesTransfer())
                    ->addTotals((new OrderRestTotalAttributesTransfer())->fromArray(
                        $order->getTotals()->toArray(), true)
                    )
                    ->setCreatedAt($order->getCreatedAt())
                    ->setCurrencyIsoCode($order->getCurrencyIsoCode()));

            foreach ($order->getItems() as $item) {
                $orderRestAttributesTransfer->addItems(
                    (new OrderItemsRestAttributesTransfer())->fromArray($item->toArray(), true)
                );
            }

            foreach ($order->getExpenses() as $expense) {
                $orderRestAttributesTransfer->addExpenses(
                    (new OrderRestExpenseAttributesTransfer())->fromArray($expense->toArray(), true)
                );
            }
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
        $orderRestAttributesTransfer = (new OrderRestAttributesTransfer())
            ->addTotals((new OrderRestTotalAttributesTransfer())->fromArray(
                $orderTransfer->getTotals()->toArray(), true)
            )
            ->setCreatedAt($orderTransfer->getCreatedAt())
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setExpenses($orderTransfer->getExpenses());

        foreach ($orderTransfer->getItems() as $item) {
            $orderRestAttributesTransfer->addItems(
                (new OrderItemsRestAttributesTransfer())->fromArray($item->toArray(), true)
            );
        }

        foreach ($orderTransfer->getExpenses() as $expense) {
            $orderRestAttributesTransfer->addExpenses(
                (new OrderRestExpenseAttributesTransfer())->fromArray($expense->toArray(), true)
            );
        }

        return $orderRestAttributesTransfer;
    }
}
