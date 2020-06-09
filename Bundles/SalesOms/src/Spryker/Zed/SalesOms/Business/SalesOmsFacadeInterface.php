<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface SalesOmsFacadeInterface
{
    /**
     * Specification:
     * - Adds OrderItemReference to SpySalesOrderItemEntityTransfer by generating the reference based on transfer fields.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithReference(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Finds sales order item transfer by order item reference.
     *
     * @api
     *
     * @param string $orderItemReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemTransfer|null
     */
    public function findSalesOrderItemByOrderItemReference(string $orderItemReference): ?SalesOrderItemTransfer;
}
