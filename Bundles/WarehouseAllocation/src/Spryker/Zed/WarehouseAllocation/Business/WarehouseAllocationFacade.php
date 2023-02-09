<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Business\WarehouseAllocationBusinessFactory getFactory()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface getRepository()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface getEntityManager()
 */
class WarehouseAllocationFacade extends AbstractFacade implements WarehouseAllocationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateWarehouses(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createWarehouseAllocator()->allocateWarehouses($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithWarehouse(array $itemTransfers): array
    {
        return $this->getFactory()->createWarehouseExpander()->expandOrderItemsWithWarehouse($itemTransfers);
    }
}
