<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence;

use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;

interface SalesServicePointEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function createSalesOrderItemServicePoint(
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
    ): SalesOrderItemServicePointTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function saveSalesOrderItemServicePointByFkSalesOrderItem(
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
    ): SalesOrderItemServicePointTransfer;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemServicePointsBySalesOrderItemIds(array $salesOrderItemIds): void;
}
