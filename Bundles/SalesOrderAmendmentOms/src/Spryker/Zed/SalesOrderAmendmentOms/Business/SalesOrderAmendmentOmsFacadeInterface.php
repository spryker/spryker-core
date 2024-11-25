<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

interface SalesOrderAmendmentOmsFacadeInterface
{
    /**
     * Specification:
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Does nothing if `CartReorderTransfer.quote.amendmentOrderReference` is not set.
     * - Validates if all order items are in order item state that has `amendable` flag.
     * - Returns `ErrorCollectionTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validateCartReorder(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer;

    /**
     * Specification:
     * - Does nothing if `CartReorderTransfer.quote.amendmentOrderReference` is not set.
     * - Triggers OMS event defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getStartOrderAmendmentEvent()} to start the order amendment process.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return void
     */
    public function startOrderAmendment(CartReorderTransfer $cartReorderTransfer): void;

    /**
     * Specification:
     * - Does nothing if `QuoteTransfer.amendmentOrderReference` is not set.
     * - Triggers OMS event defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getCancelOrderAmendmentEvent()} to cancel the order amendment process.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function cancelOrderAmendment(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Does nothing if `QuoteTransfer.amendmentOrderReference` is not set.
     * - Triggers OMS event defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getFinishOrderAmendmentEvent()} to finish the order amendment process.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function finishOrderAmendment(QuoteTransfer $quoteTransfer): void;

    /**
     * Specification:
     * - Requires `SalesOrderAmendmentTransfer.originalOrderReference` to be set.
     * - Requires `SalesOrderAmendmentTransfer.amendedOrderReference` to be set.
     * - Validates if order with provided original order reference exists.
     * - Validates if order with provided amended order reference exists.
     * - Returns `ErrorCollectionTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSalesOrderAmendment(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): ErrorCollectionTransfer;
}
