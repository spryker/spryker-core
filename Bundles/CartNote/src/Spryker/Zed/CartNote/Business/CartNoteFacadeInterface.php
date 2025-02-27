<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface CartNoteFacadeInterface
{
    /**
     * Specification:
     * - Saves cart note to order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param bool|null $forceUpdate
     *
     * @return void
     */
    public function saveOrderCartNote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer, ?bool $forceUpdate = false): void;

    /**
     * Specification:
     * - Load quote.
     * - Set cart note to quote.
     * - Save quote to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteNote(QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Load quote.
     * - Set cart note to quote item.
     * - Save quote to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteItemNote(QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.cartNote` set.
     * - Expands `CartReorderTransfer.reorderItems` with cart note if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with cart note, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with cart note set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithCartNote(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Sets `CartReorderTransfer.order.cartNote` to `CartReorderTransfer.quote.cartNote`.
     * - Returns `CartReorderTransfer` with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function expandCartReorderQuoteWithCartNote(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
