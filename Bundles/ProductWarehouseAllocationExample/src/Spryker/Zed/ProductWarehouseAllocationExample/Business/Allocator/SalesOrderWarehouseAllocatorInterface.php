<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Business\Allocator;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesOrderWarehouseAllocatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocate(OrderTransfer $orderTransfer): OrderTransfer;
}
