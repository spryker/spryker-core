<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface OrderCustomReferenceFacadeInterface
{
    /**
     * Specification:
     * - Persists `QuoteTransfer::orderCustomReference` transfer property in `spy_sales_order` schema.
     * - Does not update with empty order custom reference if `$forceUpdate` is set to false.
     * - Expects `SaveOrderTransfer::idSalesOrder` transfer property to identify target sales order.
     * - Returns with `isSuccessful=false` if order custom reference was not persisted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param bool|null $forceUpdate
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReferenceFromQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?bool $forceUpdate = false
    ): OrderCustomReferenceResponseTransfer;

    /**
     * Specification:
     * - Persists `orderCustomReference` in `spy_sales_order` schema.
     * - Validates the length of `orderCustomReference` if is less than Config::getOrderCustomReferenceMaxLength().
     * - Expects `OrderTransfer::idSalesOrder` transfer property to identify target sales order.
     * - Returns with `isSuccessful=false` if order custom reference was not persisted.
     *
     * @api
     *
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function updateOrderCustomReference(string $orderCustomReference, OrderTransfer $orderTransfer): OrderCustomReferenceResponseTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Sets `CartReorderTransfer.order.orderCustomReference` to `CartReorderTransfer.quote.orderCustomReference` if it is provided.
     * - Returns `CartReorderTransfer` with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function expandCartReorderQuoteWithOrderCustomReference(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
