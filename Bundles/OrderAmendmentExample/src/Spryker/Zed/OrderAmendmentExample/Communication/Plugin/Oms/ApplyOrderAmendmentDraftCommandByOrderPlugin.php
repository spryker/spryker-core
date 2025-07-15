<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Communication\Plugin\Oms;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\OrderAmendmentExample\OrderAmendmentExampleConfig getConfig()
 * @method \Spryker\Zed\OrderAmendmentExample\Business\OrderAmendmentExampleBusinessFactory getBusinessFactory()
 */
class ApplyOrderAmendmentDraftCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves the order reference from the provided `SpySalesOrder`
     * - Finds the corresponding sales order amendment quote using the order reference.
     * - Does nothing if the sales order amendment quote is not found.
     * - Tries to place an order with found sales order amendment quote with quote process flow set to `order-amendment`.
     * - Handles the exception during the order placement by updating the sales order amendment quote with the error message and returns an empty array.
     * - Handles the unsuccessful order placement by updating the sales order amendment quote with the error message and returning an empty array.
     * - Returns an array with updated order items if the order placement is successful.
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
        return $this->getBusinessFactory()
            ->createOrderAmendmentCheckoutProcessor()
            ->processOrderAmendmentCheckout($orderItems, $orderEntity->getOrderReference(), $orderEntity->getIdSalesOrder());
    }
}
