<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Communication\WarehouseAllocationCommunicationFactory getFactory()
 * @method \Spryker\Zed\WarehouseAllocation\Business\WarehouseAllocationFacadeInterface getFacade()
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class SalesOrderWarehouseAllocationCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Executes {@link \Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface} plugin stack.
     * - Creates warehouse allocation for order items.
     * - For `OrderTransfer.items` without `ItemTransfer.warehouse.id` specified, warehouse allocation is not created.
     *
     * @api
     *
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array<mixed>
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getFactory()
            ->createOrderMapper()
            ->mapOrderEntityAndOrderItemEntitiesToOrderTransfer($orderEntity, $orderItems, new OrderTransfer());

        $this->getFacade()->allocateWarehouses($orderTransfer);

        return [];
    }
}
