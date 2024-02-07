<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Communication\Plugin\Oms;

use Generated\Shared\Transfer\EventPaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPayment\Communication\SalesPaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 */
class SendRefundPaymentMessageCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Validates if refund process is blocked, throws `EventExecutionForbiddenException` on failure.
     * - Calculates the amount of refund using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     * - Adds the expenses cost of the entire order to refunded amount if this refund request has at least one unreimbursed item left.
     * - Sends the message using `RefundPaymentTransfer` transfer.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array<mixed>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $eventPaymentTransfer = $this->getFactory()
            ->createEventPaymentMapper()
            ->mapOrderEntityAndOrderItemEntitiesToEventPaymentTransfer($orderItems, $orderEntity, new EventPaymentTransfer());

        $this->getFacade()->sendRefundPaymentMessage($eventPaymentTransfer);

        return [];
    }
}
