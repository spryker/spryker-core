<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Communication\WarehouseAllocationCommunicationFactory getFactory()
 * @method \Spryker\Zed\WarehouseAllocation\Business\WarehouseAllocationFacadeInterface getFacade()
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class WarehouseOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.uuid` transfer property to be set.
     * - Expands order items with warehouse.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOrderItemsWithWarehouse($itemTransfers);
    }
}
