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
 * @deprecated Use {@link \Spryker\Zed\SalesPayment\Communication\Plugin\Oms\SendCapturePaymentMessageCommandPlugin} instead.
 *
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPayment\Communication\SalesPaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 */
class SendEventPaymentConfirmationPendingPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Validates if capturing process can be executed, throws `EventExecutionForbiddenException` on failure.
     * - Calculates the amount of capture using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     * - Adds the expense costs of the entire order to the capture amount if this capture request is the first for the order.
     * - Sends the message using `CapturePayment` transfer.
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

        $this->getFacade()->sendCapturePaymentMessage($eventPaymentTransfer);

        return [];
    }
}
