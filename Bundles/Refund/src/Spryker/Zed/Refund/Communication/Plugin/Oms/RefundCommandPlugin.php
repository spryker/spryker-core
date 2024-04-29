<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Plugin\Oms;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class RefundCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Calculates refund amount for given Order entity and OrderItems which should be refunded.
     * - Adds refundable amount to `Refund` transfer.
     * - Adds items with canceled amount to `Refund` transfer.
     * - Uses calculator plugins for calculation.
     * - Persists calculated refund amount.
     * - Sets calculated refund amount as canceled amount to each sales order item given.
     * - Executes {@link \Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface} plugin stack.
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
        $refundTransfer = $this->getFacade()->calculateRefund($orderItems, $orderEntity);
        $this->getFacade()->saveRefund($refundTransfer);

        return [];
    }
}
