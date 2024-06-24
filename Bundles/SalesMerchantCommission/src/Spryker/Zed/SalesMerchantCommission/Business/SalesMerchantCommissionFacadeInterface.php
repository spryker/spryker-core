<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;

interface SalesMerchantCommissionFacadeInterface
{
    /**
     * Specification:
     * - Retrieves sales merchant commission entities filtered by criteria from Persistence.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.salesMerchantCommissionConditions.salesOrderIds` to filter by sales order ids.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.salesMerchantCommissionConditions.salesOrderItemIds` to filter by sales order item ids.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `SalesMerchantCommissionCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `SalesMerchantCommissionCollectionTransfer` filled with found sales merchant commissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionCollection(
        SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
    ): SalesMerchantCommissionCollectionTransfer;

    /**
     * Specification:
     * - Requires `OrderTransfer.IdSalesOrder` to be set.
     * - Reads expanded order from Persistence.
     * - Uses {@link \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacade::calculateMerchantCommission()} to calculate merchant commissions.
     * - Persists sales merchant commissions for order.
     * - Updates order totals and order items with merchant commissions amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createSalesMerchantCommissions(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Requires `CalculableObjectTransfer.totals` to be set.
     * - Expects `CalculableObjectTransfer.originalOrder.idSalesOrder` to be provided.
     * - Expects `CalculableObjectTransfer.items` to be provided.
     * - Retrieves sales merchant commissions for the order from Persistence.
     * - Updates `CalculableObjectTransfer` with merchant commissions for items and totals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer;

    /**
     * Specification:
     * - Requires `ItemTransfer.idSalesOrderItem` to be set.
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Retrieves sales merchant commissions for provided items from Persistence.
     * - Updates `SalesMerchantCommissionTransfer.refundedAmount` with refunded amount.
     * - Persists sales merchant commissions with new refunded amounts.
     * - Recalculates order.
     * - Updates order totals and order items with merchant commissions amount.
     * - Returns `OrderTransfer` with updated merchant commissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function refundMerchantCommissions(OrderTransfer $orderTransfer, array $itemTransfers): OrderTransfer;

    /**
     * Specification:
     * - Sanitizes merchant commission related fields in quote items.
     * - Sanitizes merchant commission related fields in quote totals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeMerchantCommissionFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
