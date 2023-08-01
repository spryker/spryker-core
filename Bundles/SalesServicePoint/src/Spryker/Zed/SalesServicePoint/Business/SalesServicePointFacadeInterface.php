<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesServicePointFacadeInterface
{
    /**
     * Specification:
     * - Persists service point information from `ItemTransfer` in Quote to `spy_sales_order_item_service_point` table.
     * - Expects service point to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemServicePointsFromQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Requires `ItemTransfer.idSalesOrderItem` property to be set.
     * - Expands `ItemTransfer` with `SalesOrderItemServicePointTransfer` if service point is available.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithServicePoint(array $itemTransfers): array;
}
