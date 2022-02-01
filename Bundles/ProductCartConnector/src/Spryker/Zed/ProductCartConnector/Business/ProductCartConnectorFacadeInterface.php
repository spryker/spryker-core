<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Reads a persisted concrete product from database.
     * - Expands the items of the CartChangeTransfer with the concrete product's data.
     * - Returns the expanded CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     * - Checks added to cart products on existing
     * - Returns pre-check transfer with error messages (in negative case)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItems(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     *  - Removes inactive items from quote.
     *  - Adds note to messages about removed items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Reads persisted URLs from database by abstract product IDs.
     * - Expands CartChangeTransfer items with abstract product URLs.
     * - Returns expanded CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemTransfersWithUrls(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Does nothing if quote items are empty.
     * - Requires either `QuoteTransfer.item.sku` or `QuoteTransfer.item.abstractSku` to be set.
     * - Validates if concrete products with sku `QuoteTransfer.item.sku` exist and active.
     * - If `QuoteTransfer.item.sku` is missing, validates if abstract products with sku `QuoteTransfer.item.abstractSku` exist.
     * - Adds corresponding errors to `CheckoutResponseTransfer` in case of failed validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutQuoteItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;
}
