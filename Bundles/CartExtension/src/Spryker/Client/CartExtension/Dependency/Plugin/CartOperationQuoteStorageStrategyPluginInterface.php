<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface CartOperationQuoteStorageStrategyPluginInterface
{
    /**
     * Specification:
     * - Makes zed request.
     * - Adds items to quote.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Removes items from quote.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Updates quantity for given items.
     * - Stores quote in session internally after success zed request.
     * - Returns response with updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(CartChangeTransfer $cartChangeTransfer): QuoteResponseTransfer;
}
