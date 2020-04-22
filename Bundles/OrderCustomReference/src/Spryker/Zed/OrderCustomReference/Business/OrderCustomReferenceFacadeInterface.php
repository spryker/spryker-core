<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface OrderCustomReferenceFacadeInterface
{
    /**
     * Specification:
     * - Persists `QuoteTransfer::orderCustomReference` transfer property in `spy_sales_order` schema.
     * - Expects `SaveOrderTransfer::idSalesOrder` transfer property to identify target sales order.
     * - Returns with `isSuccessful=false` if order custom reference was not persisted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReferenceFromQuote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): OrderCustomReferenceResponseTransfer;

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
}
