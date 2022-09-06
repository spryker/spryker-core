<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\SalesPayment\Communication\Plugin\Oms\SendEventPaymentConfirmationPendingPlugin} instead.
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 */
class SendEventPaymentConfirmationPendingPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Sends event if total count of order items above zero.
     * - Uses orderTransfer.orderReference, orderTransfer.currencyIsoCode, order item ids and total count to send the event.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderItemIds = [];
        $orderItemsTotal = 0;

        foreach ($orderItems as $orderItem) {
            $orderItemIds[] = $orderItem->getIdSalesOrderItem();
            $orderItemsTotal += $orderItem->getPriceToPayAggregation();
        }

        $orderTransfer = $this->getFactory()
            ->createOrderMapper()
            ->mapSalesOrderEntityToSalesOrderTransfer($orderEntity, new OrderTransfer());

        $this->getFacade()->sendEventPaymentConfirmationPending($orderItemIds, $orderItemsTotal, $orderTransfer);

        return [];
    }
}
