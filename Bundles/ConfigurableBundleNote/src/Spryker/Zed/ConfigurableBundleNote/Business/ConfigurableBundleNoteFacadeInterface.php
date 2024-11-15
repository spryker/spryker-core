<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleNoteFacadeInterface
{
    /**
     * Specification:
     * - Retrieves Quote from database by idQuote.
     * - Updates configured bundle with note.
     * - Returns `isSuccess=true` if note was successfully set and quote was updated or `isSuccess=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.salesOrderConfiguredBundle` and `ItemTransfer.salesOrderConfiguredBundleItem` set.
     * - Expands `CartReorderTransfer.reorderItems` with configured bundle note if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with configured bundle note, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with configured bundle note set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithConfigurableBundle(
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer;
}
