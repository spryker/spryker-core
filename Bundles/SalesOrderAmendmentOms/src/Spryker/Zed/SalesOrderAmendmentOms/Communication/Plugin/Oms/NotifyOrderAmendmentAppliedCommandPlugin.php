<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class NotifyOrderAmendmentAppliedCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves the order reference from the provided `SpySalesOrder`.
     * - Finds the corresponding sales order amendment quote using the order reference.
     * - Does nothing if the sales order amendment quote is not found.
     * - Sends a mail notification that the order amendment has been applied successfully if the sales order amendment quote is found.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array<mixed>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $this->getBusinessFactory()
            ->createOrderAmendmentStatusMailNotificationSender()
            ->notifyOrderAmendmentApplied($orderEntity->getOrderReference(), $orderEntity->getIdSalesOrder());

        return [];
    }
}
