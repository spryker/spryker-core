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
     * - Persists `orderCustomReference` in `spy_sales_order` schema.
     * - Expects SaveOrderTransfer::idSalesOrder to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Persists `orderCustomReference` in `spy_sales_order` schema.
     * - Expects OrderTransfer::idSalesOrder to be provided.
     *
     * @api
     *
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function updateOrderCustomReference(
        string $orderCustomReference,
        OrderTransfer $orderTransfer
    ): OrderCustomReferenceResponseTransfer;

    /**
     * Specification:
     * - Returns order custom reference related Quote fields allowed for saving.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array;
}
